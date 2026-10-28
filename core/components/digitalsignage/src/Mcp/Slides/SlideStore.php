<?php
namespace DigitalSignage\Mcp\Slides;

use MODX\Revolution\modX;
use MODX\Revolution\Sources\modMediaSource;

class SlideStore
{
    public const TYPES = [
        'standard' => 'default',
        'media' => 'media',
    ];

    public const IMAGE_FOLDER = 'ai';

    protected string $corePath;

    public function __construct(protected modX $modx, protected ?ImageDownloader $downloader = null)
    {
        $this->downloader ??= new ImageDownloader();
        $this->corePath = (string) $modx->getOption('digitalsignage.core_path', null, $modx->getOption('core_path') . 'components/digitalsignage/');
        $modx->getService('error', 'error.modError');
        $modx->getService('digitalsignage', 'DigitalSignage', $this->corePath . 'model/digitalsignage/');
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function playlists(): array
    {
        $playlists = [];

        foreach ($this->modx->getCollection('DigitalSignageBroadcasts') as $broadcast) {
            if (!$broadcast->hasResource()) {
                continue;
            }

            $playlists[] = [
                'id' => (int) $broadcast->get('id'),
                'name' => (string) $broadcast->getResource()->get('pagetitle'),
                'slides' => count($this->slideRows((int) $broadcast->get('id'))),
                'needs_sync' => (bool) $broadcast->needSync(),
            ];
        }

        usort($playlists, fn ($a, $b) => $a['id'] <=> $b['id']);

        return $playlists;
    }

    public function playlist(int $id): ?\DigitalSignageBroadcasts
    {
        $broadcast = $this->modx->getObject('DigitalSignageBroadcasts', $id);

        return $broadcast && $broadcast->hasResource() ? $broadcast : null;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function slides(int $playlistId): array
    {
        $slides = [];

        foreach ($this->slideRows($playlistId) as $position => $row) {
            $slide = $row->getOne('getSlide');

            if (!$slide) {
                continue;
            }

            $type = $slide->getOne('getSlideType');
            $data = (array) @unserialize((string) $slide->get('data'));
            $content = trim(preg_replace('/\s+/', ' ', html_entity_decode(preg_replace('/<[^>]*>/', ' ', (string) ($data['content'] ?? '')))));

            $slides[] = [
                'id' => (int) $slide->get('id'),
                'position' => $position + 1,
                'name' => (string) $slide->get('name'),
                'type' => $type ? (string) $type->get('key') : null,
                'duration' => (int) $slide->get('time'),
                'published' => (int) $slide->get('published') === 1,
                'edited' => (string) $slide->get('editedon'),
                'text' => mb_strlen($content) > 80 ? mb_substr($content, 0, 80) . '...' : $content,
                'image' => $this->imagePath($data['image'] ?? ''),
                'video_url' => (string) ($data['video_extern'] ?? ''),
            ];
        }

        return $slides;
    }

    /**
     * @param list<array<string, mixed>> $slides
     */
    public function revision(int $playlistId, array $slides): string
    {
        return md5(serialize([$playlistId, $slides]));
    }

    /**
     * @param array{type: string, title: string, content: ?string, image_url: ?string, video_url: ?string, duration: ?int} $input
     *
     * @return array{slide_id: int, synced: bool}
     *
     * @throws SlideRejected
     */
    public function create(\DigitalSignageBroadcasts $broadcast, array $input): array
    {
        $type = $this->modx->getObject('DigitalSignageSlidesTypes', ['key' => self::TYPES[$input['type']]]);

        if (!$type) {
            throw new SlideRejected(
                'The slide type "' . $input['type'] . '" is not installed on this site.',
                'This is a site configuration problem. Tell the user the slide type is missing and that they should contact their site administrator.'
            );
        }

        $fields = (array) @unserialize((string) $type->get('data'));
        $image = $input['image_url'] ? $this->storeImage($input['image_url']) : '';

        $properties = [
            'name' => mb_substr(trim($input['title']), 0, 75),
            'type' => (int) $type->get('id'),
            'time' => $input['duration'] ?: (int) $type->get('time'),
            'published' => 1,
            'broadcasts' => [(int) $broadcast->get('id')],
            'data_image' => json_encode(['image' => $image]),
            'data_ticker' => (string) ($fields['ticker']['default_value'] ?? '1'),
            'data_fullscreen' => (string) ($fields['fullscreen']['default_value'] ?? '1'),
        ];

        if ($input['type'] === 'standard') {
            $properties['data_content'] = (string) $input['content'];
        } else {
            $properties['data_video_extern'] = (string) $input['video_url'];
            $properties['data_video_intern'] = json_encode(['image' => '']);
        }

        $this->modx->error->reset();
        $response = $this->modx->runProcessor('mgr/slides/create', $properties, ['processors_path' => $this->corePath . 'processors/']);

        if ($response->isError()) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, '[DigitalSignage MCP] create_slide failed: ' . $response->getMessage());

            throw new SlideRejected(
                'The slide could not be saved: ' . ($response->getMessage() ?: 'unknown error') . '.',
                'This is a problem on the site, not with the user\'s input. Tell the user the slide was not created and do not retry.'
            );
        }

        $id = (int) ($response->getObject()['id'] ?? 0);

        return ['slide_id' => $id, 'synced' => $this->sync($broadcast)];
    }

    protected function sync(\DigitalSignageBroadcasts $broadcast): bool
    {
        try {
            $synced = (bool) $broadcast->sync();
        } catch (\Throwable $e) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, '[DigitalSignage MCP] sync failed: ' . $e->getMessage());

            return false;
        }

        if (!$synced) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, '[DigitalSignage MCP] sync of playlist ' . $broadcast->get('id') . ' failed: the export folder is not writable');
        }

        return $synced;
    }

    /**
     * @throws SlideRejected
     */
    protected function storeImage(string $url): string
    {
        $image = $this->downloader->download($url);

        /** @var modMediaSource|null $source */
        $source = $this->modx->getObject(modMediaSource::class, (int) $this->modx->getOption('digitalsignage.media_source'));

        if (!$source || !$source->initialize()) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, '[DigitalSignage MCP] media source ' . $this->modx->getOption('digitalsignage.media_source') . ' is not available');

            throw new SlideRejected(
                'The media source for slide images is not available.',
                'This is a site configuration problem. Tell the user the image could not be stored and the slide was not created.'
            );
        }

        $name = date('Ymd-His') . '-' . bin2hex(random_bytes(4)) . '.' . $image['extension'];

        if (!$source->createObject(self::IMAGE_FOLDER, $name, $image['content'])) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, '[DigitalSignage MCP] could not save ' . $name . ' in the media source: ' . json_encode($source->getErrors()));

            throw new SlideRejected(
                'The image could not be saved on the site.',
                'This is a problem on the site, not with the image. Tell the user the slide was not created and do not retry.'
            );
        }

        return self::IMAGE_FOLDER . '/' . $name;
    }

    protected function imagePath(mixed $value): ?string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        $decoded = json_decode($value, true);
        $path = is_array($decoded) ? (string) ($decoded['image'] ?? '') : $value;

        return $path === '' ? null : $path;
    }

    /**
     * @return list<object>
     */
    protected function slideRows(int $playlistId): array
    {
        $query = $this->modx->newQuery('DigitalSignageBroadcastsSlides', ['broadcast_id' => $playlistId]);
        $query->sortby('sortindex', 'ASC');
        $query->sortby('id', 'ASC');

        return array_values($this->modx->getCollection('DigitalSignageBroadcastsSlides', $query));
    }
}
