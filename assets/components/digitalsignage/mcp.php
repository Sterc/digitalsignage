<?php
use DigitalSignage\Mcp\DigitalSignageServer;
use DigitalSignage\Mcp\Http\HttpEndpoint;
use DigitalSignage\Mcp\Modx\ModxLogger;
use DigitalSignage\Mcp\Slides\SlideStore;
use Mcp\Server\Session\FileSessionStore;
use MODX\Revolution\modX;

define('MODX_API_MODE', true);

require_once dirname(__DIR__, 3) . '/config.core.php';
require_once MODX_CORE_PATH . 'vendor/autoload.php';

$modx = new modX('', ['session_enabled' => false]);
$modx->initialize('mgr');

$corePath = (string) $modx->getOption('digitalsignage.core_path', null, $modx->getOption('core_path') . 'components/digitalsignage/');
require_once $corePath . 'vendor/autoload.php';

$logger = new ModxLogger($modx);

$builder = DigitalSignageServer::builder(new SlideStore($modx))
    ->setLogger($logger)
    ->setSession(new FileSessionStore($modx->getOption('core_path') . 'cache/digitalsignage/mcp-sessions/'));

(new HttpEndpoint($builder, (string) $modx->getOption('digitalsignage.mcp_api_key'), $logger))->run();
