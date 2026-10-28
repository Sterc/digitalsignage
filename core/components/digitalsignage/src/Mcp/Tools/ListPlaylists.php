<?php
namespace DigitalSignage\Mcp\Tools;

use Mcp\Schema\Tool;
use Mcp\Schema\ToolAnnotations;

class ListPlaylists extends DigitalSignageTool
{
    public function definition(): Tool
    {
        return new Tool(
            name: 'list_playlists',
            title: 'List playlists',
            inputSchema: [
                'type' => 'object',
                'properties' => new \stdClass(),
            ],
            description: 'List the playlists (broadcasts) that are shown on the digital signage screens, with their id, name and number of slides. Use this when the user asks what playlists exist or to find the playlist_id that list_slides and create_slide need. needs_sync is true when the playlist has changes that the screens do not show yet.',
            annotations: new ToolAnnotations(readOnlyHint: true, openWorldHint: false),
        );
    }

    protected function handle(array $arguments): array
    {
        return [
            'success' => true,
            'playlists' => $this->store->playlists(),
        ];
    }
}
