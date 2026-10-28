<?php
namespace DigitalSignage\Mcp\Modx;

use MODX\Revolution\modX;
use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;

class ModxLogger extends AbstractLogger
{
    public function __construct(protected modX $modx)
    {
    }

    public function log($level, string|\Stringable $message, array $context = []): void
    {
        $modxLevel = match ($level) {
            LogLevel::EMERGENCY, LogLevel::ALERT, LogLevel::CRITICAL, LogLevel::ERROR => modX::LOG_LEVEL_ERROR,
            LogLevel::WARNING => modX::LOG_LEVEL_WARN,
            LogLevel::NOTICE, LogLevel::INFO => modX::LOG_LEVEL_INFO,
            default => modX::LOG_LEVEL_DEBUG,
        };

        $exception = $context['exception'] ?? null;

        if ($exception instanceof \Throwable) {
            $message .= ': ' . $exception->getMessage() . ' in ' . $exception->getFile() . ':' . $exception->getLine();
        }

        $this->modx->log($modxLevel, '[DigitalSignage MCP] ' . $message);
    }
}
