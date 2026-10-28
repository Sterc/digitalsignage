<?php
namespace DigitalSignage\Mcp\Http;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ApiKeyMiddleware implements MiddlewareInterface
{
    public function __construct(
        protected string $apiKey,
        protected ResponseFactoryInterface $responseFactory,
        protected StreamFactoryInterface $streamFactory,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // CORS preflights never carry credentials.
        if ($request->getMethod() === 'OPTIONS') {
            return $handler->handle($request);
        }

        if ($this->apiKey === '') {
            return $this->refuse(403, 'The MCP endpoint is disabled because no API key has been configured.');
        }

        $given = $request->getHeaderLine('X-Api-Key');

        if ($given === '' && preg_match('/^Bearer\s+(.+)$/i', $request->getHeaderLine('Authorization'), $matches)) {
            $given = trim($matches[1]);
        }

        if ($given === '' || !hash_equals($this->apiKey, $given)) {
            return $this->refuse(401, 'A valid API key is required.')
                ->withHeader('WWW-Authenticate', 'Bearer realm="mcp"');
        }

        return $handler->handle($request);
    }

    protected function refuse(int $status, string $message): ResponseInterface
    {
        return $this->responseFactory->createResponse($status)
            ->withHeader('Content-Type', 'application/json')
            ->withBody($this->streamFactory->createStream(json_encode(['error' => $message])));
    }
}
