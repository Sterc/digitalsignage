<?php
namespace DigitalSignage\Mcp;

use DigitalSignage\Mcp\Slides\SlideStore;
use DigitalSignage\Mcp\Tools\CreateSlide;
use DigitalSignage\Mcp\Tools\DigitalSignageTool;
use DigitalSignage\Mcp\Tools\ListPlaylists;
use DigitalSignage\Mcp\Tools\ListSlides;
use Mcp\Server;
use Mcp\Server\Builder;

class DigitalSignageServer
{
    public const NAME = 'Digital Signage';
    public const VERSION = '2.0.0';

    public const INSTRUCTIONS = 'MCP server for the digital signage screens of a customer site. Screens show playlists (broadcasts); a playlist is an ordered list of slides. Use list_playlists to find the playlist, list_slides to see its slides, and create_slide to add a new one. You can create two kinds of slide: "standard" (title, optional HTML text, optional image) and "media" (title plus image and/or an external video link); other slide types may exist on the site but cannot be created here. Always call list_slides for the playlist right before create_slide and pass its revision; create_slide refuses a missing or outdated revision so that you never add to a playlist you have not seen. A new slide is appended at the end of the playlist and published, and the playlist is synchronised so the screens pick it up within about 2 minutes; if the synchronisation fails the result says so and you must tell the user. Slides cannot be edited or removed with these tools. Because a slide goes live on real screens, confirm the playlist, title, text and image with the user before calling create_slide. An image must be a public, direct http(s) link to a JPEG, PNG, GIF or WebP file (max 10 MB); never invent links or text.';

    /** @var list<class-string<DigitalSignageTool>> */
    public const TOOLS = [
        ListPlaylists::class,
        ListSlides::class,
        CreateSlide::class,
    ];

    public static function builder(SlideStore $store, ?Builder $builder = null): Builder
    {
        $builder ??= Server::builder();

        $builder
            ->setServerInfo(self::NAME, self::VERSION)
            ->setInstructions(self::INSTRUCTIONS);

        foreach (self::TOOLS as $class) {
            $tool = new $class($store);
            $builder->add($tool->definition(), $tool);
        }

        return $builder;
    }
}
