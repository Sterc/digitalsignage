<?php
namespace DigitalSignage\Mcp\Tools;

use DigitalSignage\Mcp\Slides\SlideRejected;
use Mcp\Schema\Tool;
use Mcp\Schema\ToolAnnotations;

class CreateSlide extends DigitalSignageTool
{
    protected const MIN_DURATION = 3;
    protected const MAX_DURATION = 300;

    public function definition(): Tool
    {
        return new Tool(
            name: 'create_slide',
            title: 'Create slide',
            inputSchema: [
                'type' => 'object',
                'properties' => [
                    'playlist_id' => [
                        'type' => 'integer',
                        'minimum' => 1,
                        'description' => 'The id of the playlist to add the slide to, from list_playlists.',
                    ],
                    'revision' => [
                        'type' => 'string',
                        'description' => 'The revision string returned by the latest list_slides call for this same playlist. Required; a stale or missing revision is refused.',
                    ],
                    'type' => [
                        'type' => 'string',
                        'enum' => ['standard', 'media'],
                        'description' => 'standard: a title with optional HTML text and an optional image. media: a title with an image and/or an external video (YouTube/Vimeo link).',
                    ],
                    'title' => [
                        'type' => 'string',
                        'minLength' => 1,
                        'maxLength' => 75,
                        'description' => 'The heading shown on the slide. Maximum 75 characters.',
                    ],
                    'content' => [
                        'type' => 'string',
                        'description' => 'Only for type standard: the slide text as simple HTML (p, h2, h3, strong, em, br, ul, ol, li). Keep it short; it is read from a distance on a screen.',
                    ],
                    'image_url' => [
                        'type' => 'string',
                        'description' => 'Public http(s) link to a JPEG, PNG, GIF or WebP image, maximum 10 MB. The site downloads it and stores it itself. Must be a direct link to the image file, not a web page.',
                    ],
                    'video_url' => [
                        'type' => 'string',
                        'description' => 'Only for type media: link to an external video, such as a YouTube or Vimeo URL.',
                    ],
                    'duration' => [
                        'type' => 'integer',
                        'minimum' => self::MIN_DURATION,
                        'maximum' => self::MAX_DURATION,
                        'description' => 'How many seconds the slide is shown. Omit to use the default of the slide type (10 seconds).',
                    ],
                ],
                'required' => ['playlist_id', 'revision', 'type', 'title'],
            ],
            description: 'Add one new slide at the END of a playlist and publish it to the screens (the playlist is synchronised, so screens show it within about 2 minutes). Rules: (1) call list_slides for this playlist right before and pass its revision; (2) ask the user to confirm the playlist, title, text and image before calling, because the slide goes live on real screens and cannot be removed with these tools; (3) never invent text or image links the user did not provide or approve. A standard slide needs a title and may have content and an image; a media slide needs an image_url or a video_url. Returns the slide id, its position and a new revision.',
            annotations: new ToolAnnotations(readOnlyHint: false, destructiveHint: false, idempotentHint: false, openWorldHint: true),
        );
    }

    protected function handle(array $arguments): array
    {
        $playlistId = (int) ($arguments['playlist_id'] ?? 0);
        $type = (string) ($arguments['type'] ?? '');
        $title = trim((string) ($arguments['title'] ?? ''));
        $content = isset($arguments['content']) ? trim((string) $arguments['content']) : null;
        $imageUrl = isset($arguments['image_url']) ? trim((string) $arguments['image_url']) : null;
        $videoUrl = isset($arguments['video_url']) ? trim((string) $arguments['video_url']) : null;
        $duration = isset($arguments['duration']) ? (int) $arguments['duration'] : null;

        $problem = $this->problem($type, $title, $content, $imageUrl, $videoUrl, $duration);

        if ($problem !== null) {
            return $this->failure($problem, 'Do not retry with invented values. Ask the user for what is missing or wrong, then call create_slide again.');
        }

        $broadcast = $this->store->playlist($playlistId);

        if (!$broadcast) {
            return $this->unknownPlaylist($playlistId);
        }

        $current = $this->store->revision($playlistId, $this->store->slides($playlistId));

        if (!hash_equals($current, (string) ($arguments['revision'] ?? ''))) {
            return $this->failure(
                'The revision is missing or outdated: the playlist has changed since you last looked at it, or list_slides was not called.',
                'Call list_slides for this playlist, check the result still matches what the user expects (tell the user if someone else added slides), and call create_slide again with the new revision. The slide was NOT created.'
            );
        }

        try {
            $created = $this->store->create($broadcast, [
                'type' => $type,
                'title' => $title,
                'content' => $content,
                'image_url' => $imageUrl ?: null,
                'video_url' => $videoUrl ?: null,
                'duration' => $duration,
            ]);
        } catch (SlideRejected $e) {
            return $this->failure($e->getMessage(), $e->instructions ?: 'Tell the user the slide was not created.');
        }

        $slides = $this->store->slides($playlistId);
        $position = null;

        foreach ($slides as $slide) {
            if ($slide['id'] === $created['slide_id']) {
                $position = $slide['position'];
            }
        }

        return [
            'success' => true,
            'slide_id' => $created['slide_id'],
            'playlist_id' => $playlistId,
            'position' => $position,
            'total' => count($slides),
            'revision' => $this->store->revision($playlistId, $slides),
            'synced' => $created['synced'],
            'instructions_for_assistant' => $created['synced']
                ? 'The slide was added at the end of the playlist and the playlist was synchronised; the screens show it within about 2 minutes. Confirm this to the user in your own words.'
                : 'The slide was created and added at the end of the playlist, but synchronising the playlist FAILED, so the screens do not show it yet. Tell the user this clearly: they must press "Synchronize" for this playlist in the Digital Signage manager, or ask their site administrator. Do not claim the slide is live.',
        ];
    }

    protected function problem(string $type, string $title, ?string $content, ?string $imageUrl, ?string $videoUrl, ?int $duration): ?string
    {
        if (!in_array($type, ['standard', 'media'], true)) {
            return 'type must be "standard" or "media".';
        }

        if ($title === '' || mb_strlen($title) > 75) {
            return 'title is required and may be at most 75 characters.';
        }

        if ($duration !== null && ($duration < self::MIN_DURATION || $duration > self::MAX_DURATION)) {
            return 'duration must be between ' . self::MIN_DURATION . ' and ' . self::MAX_DURATION . ' seconds.';
        }

        foreach (['image_url' => $imageUrl, 'video_url' => $videoUrl] as $field => $url) {
            if ($url !== null && $url !== '' && !preg_match('#^https?://[^\s]+$#i', $url)) {
                return "{$field} must be a full http or https link.";
            }
        }

        if ($type === 'standard' && $videoUrl) {
            return 'video_url can only be used with type media.';
        }

        if ($type === 'media' && $content) {
            return 'content can only be used with type standard.';
        }

        if ($type === 'media' && !$imageUrl && !$videoUrl) {
            return 'A media slide needs an image_url or a video_url.';
        }

        return null;
    }
}
