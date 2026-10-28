<?php
namespace DigitalSignage\Mcp\Tools;

use DigitalSignage\Mcp\Slides\SlideStore;
use Mcp\Schema\Content\TextContent;
use Mcp\Schema\Result\CallToolResult;
use Mcp\Schema\Tool;
use Mcp\Server\ClientGateway;
use Mcp\Server\Handler\ToolHandlerInterface;

abstract class DigitalSignageTool implements ToolHandlerInterface
{
    public function __construct(protected SlideStore $store)
    {
    }

    abstract public function definition(): Tool;

    /**
     * @param array<string, mixed> $arguments
     *
     * @return array<string, mixed>
     */
    abstract protected function handle(array $arguments): array;

    public function execute(array $arguments, ClientGateway $gateway): CallToolResult
    {
        $data = $this->handle($arguments);

        return new CallToolResult(
            [new TextContent(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE))],
            ($data['success'] ?? true) === false,
        );
    }

    /**
     * @return array<string, mixed>
     */
    protected function failure(string $error, string $instructions): array
    {
        return [
            'success' => false,
            'error' => $error,
            'instructions_for_assistant' => $instructions,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function unknownPlaylist(int $id): array
    {
        return $this->failure(
            "Playlist {$id} does not exist.",
            'Call list_playlists and use one of the ids it returns. If the user named a playlist, pick the matching one; if several could match, ask the user which one they mean.'
        );
    }
}
