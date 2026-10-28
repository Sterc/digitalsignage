<?php
namespace DigitalSignage\Mcp\Http;

use Mcp\Server\Builder;
use Mcp\Server\Transport\Http\Middleware\CorsMiddleware;
use Mcp\Server\Transport\StreamableHttpTransport;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

class HttpEndpoint
{
    public function __construct(
        protected Builder $builder,
        protected string $apiKey,
        protected ?LoggerInterface $logger = null,
    ) {
    }

    public function run(): void
    {
        $factory = new Psr17Factory();
        $request = (new ServerRequestCreator($factory, $factory, $factory, $factory))->fromGlobals();

        static::emit($this->handle($request));
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $factory = new Psr17Factory();

        $transport = new StreamableHttpTransport(
            $request,
            $factory,
            $factory,
            $this->logger,
            [
                new CorsMiddleware(),
                new ApiKeyMiddleware($this->apiKey, $factory, $factory),
            ],
        );

        return $this->builder->build()->run($transport);
    }

    public static function emit(ResponseInterface $response): void
    {
        // Stray output, such as a PHP notice, would corrupt the JSON response.
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        http_response_code($response->getStatusCode());

        foreach ($response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header("{$name}: {$value}", false);
            }
        }

        $body = $response->getBody();

        if ($body->isSeekable()) {
            $body->rewind();
        }

        // A streamed (SSE) body writes its events itself while it is being read.
        while (!$body->eof()) {
            echo $body->read(8192);
            flush();
        }
    }
}
