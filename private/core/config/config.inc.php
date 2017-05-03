<?php
if (file_exists(__DIR__ . '/config.environment.inc.php')) {
    /* Include configuration for correct environment */
    include_once __DIR__ . '/config.environment.inc.php';
} else {
    $database_type = 'mysql';
    $database_server = '192.168.1.247';
    $database_user = 'sterc';
    $database_password = 'supergr0v3r';
    $database_connection_charset = 'utf8';
    $dbase = 'narrowcasting';
    $table_prefix = 'modx_';
    $database_dsn = 'mysql:host=' . $database_server . ';dbname=' . $dbase . ';charset=utf8';
}

$config_options = [];
$driver_options = [];

$lastInstallTime = 1443776191;

$site_id = 'modx560e46bf8365a6.51819041';
$site_sessionname = 'SN525ffa88544b5';
$https_port = '443';
$uuid = 'c52cac54-5a24-4f70-a568-f7e9803b791f';

if (!defined('MODX_CORE_PATH')) {
    $modx_core_path = dirname(__DIR__) . '/';
    define('MODX_CORE_PATH', $modx_core_path);
}

if (!defined('MODX_PROCESSORS_PATH')) {
    $modx_processors_path = dirname(__DIR__) . '/model/modx/processors/';
    define('MODX_PROCESSORS_PATH', $modx_processors_path);
}

if (!defined('MODX_CONNECTORS_PATH')) {
    $modx_connectors_path = dirname(dirname(dirname(__DIR__))) . '/webroot/connectors/';
    $modx_connectors_url = '/connectors/';
    define('MODX_CONNECTORS_PATH', $modx_connectors_path);
    define('MODX_CONNECTORS_URL', $modx_connectors_url);
}

if (!defined('MODX_MANAGER_PATH')) {
    $modx_manager_path = dirname(dirname(dirname(__DIR__))) . '/webroot/manager/';
    $modx_manager_url = '/manager/';
    define('MODX_MANAGER_PATH', $modx_manager_path);
    define('MODX_MANAGER_URL', $modx_manager_url);
}

if (!defined('MODX_BASE_PATH')) {
    $modx_base_path = dirname(dirname(dirname(__DIR__))) . '/webroot/';
    $modx_base_url = '/';
    define('MODX_BASE_PATH', $modx_base_path);
    define('MODX_BASE_URL', $modx_base_url);
}

if (defined('PHP_SAPI') && (PHP_SAPI === 'cli' || PHP_SAPI === 'embed')) {
    $isSecureRequest = false;
} else {
    $isSecureRequest = (
        (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) === 'on') ||
        $_SERVER['SERVER_PORT'] === $https_port
    );
}

if (!defined('MODX_URL_SCHEME')) {
    $url_scheme = $isSecureRequest ? 'https://' : 'http://';
    define('MODX_URL_SCHEME', $url_scheme);
}

$http_host = '';
if (!defined('MODX_HTTP_HOST')) {
    if (defined('PHP_SAPI') && (PHP_SAPI === 'cli' || PHP_SAPI === 'embed')) {
        $http_host = 'sterc.nl';
        define('MODX_HTTP_HOST', $http_host);
    } else {
        $http_host = array_key_exists('HTTP_HOST', $_SERVER) ? $_SERVER['HTTP_HOST'] : 'sterc.nl';
        if ((int) $_SERVER['SERVER_PORT'] !== 80) {
            $http_host = str_replace(':' . $_SERVER['SERVER_PORT'], '', $http_host); // remove port from HTTP_HOST
        }
        $http_host .= ((int) $_SERVER['SERVER_PORT'] === 80 || $isSecureRequest) ? '' : ':' . $_SERVER['SERVER_PORT'];
        define('MODX_HTTP_HOST', $http_host);
    }
}

if (!defined('MODX_SITE_URL')) {
    $site_url = $url_scheme . $http_host . MODX_BASE_URL;
    define('MODX_SITE_URL', $site_url);
}

if (!defined('MODX_ASSETS_PATH')) {
    $modx_assets_path = dirname(dirname(dirname(__DIR__))) . '/webroot/assets/';
    $modx_assets_url = '/assets/';
    define('MODX_ASSETS_PATH', $modx_assets_path);
    define('MODX_ASSETS_URL', $modx_assets_url);
}

if (!defined('MODX_LOG_LEVEL_FATAL')) {
    define('MODX_LOG_LEVEL_FATAL', 0);
    define('MODX_LOG_LEVEL_ERROR', 1);
    define('MODX_LOG_LEVEL_WARN', 2);
    define('MODX_LOG_LEVEL_INFO', 3);
    define('MODX_LOG_LEVEL_DEBUG', 4);
}

if (!defined('MODX_CACHE_DISABLED')) {
    $modx_cache_disabled = false;
    define('MODX_CACHE_DISABLED', $modx_cache_disabled);
}
