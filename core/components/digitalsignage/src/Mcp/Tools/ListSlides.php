<?php
namespace DigitalSignage\Mcp\Tools;

use Mcp\Schema\Tool;
use Mcp\Schema\ToolAnnotations;

class ListSlides extends DigitalSignageTool
{
    public function definition(): Tool
    {
        return new Tool(
            name: 'list_slides',
            title: 'List slides of a playlist',
            inputSchema: [
                'type' => 'object',
                'properties' => [
                    'playlist_id' => [
                        'type' => 'integer',
                        'minimum' => 1,
                        'description' => 'The id of the playlist, from list_playlists.',
                    ],
                ],
                'required' => ['playlist_id'],
            ],
            description: 'List all slides of one playlist in the order they are shown (position 1 is first), with name, type (default = standard text/image slide, media = image/video slide, other types are special slides), duration in seconds, published flag, a short text excerpt and the image path. The result contains a revision string: always call this tool before create_slide and pass the returned revision to it, so you never add a slide to a playlist whose content you have not seen. Call it again whenever the playlist may have changed.',
            annotations: new ToolAnnotations(readOnlyHint: true, openWorldHint: false),
        );
    }

    protected function handle(array $arguments): array
    {
        $playlistId = (int) $arguments['playlist_id'];

        if (!$this->store->playlist($playlistId)) {
            return $this->unknownPlaylist($playlistId);
        }

        $slides = $this->store->slides($playlistId);

        return [
            'success' => true,
            'playlist_id' => $playlistId,
            'total' => count($slides),
            'revision' => $this->store->revision($playlistId, $slides),
            'slides' => $slides,
        ];
    }
}
