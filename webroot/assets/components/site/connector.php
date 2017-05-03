<?php
/**
 * Valid actions must contain processor path to allow access.
 * Example:
 * 'web/resource/getlist'
 *
 * @package site
 */
$validActions = [];

if (!empty($_REQUEST['action']) && in_array($_REQUEST['action'], $validActions)) {
    @session_cache_limiter('public');
    define('MODX_REQP', false);
}
define('MODX_API_MODE', true);

/*
 * This goes to the www.domainname.tld/index.php.
 */
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/index.php';
$corePath = $modx->getOption('site.core_path', null, $modx->getOption('core_path') . 'components/site/');
require_once $corePath . 'model/site/site.class.php';

$modx->site = new Site($modx);
$modx->lexicon->load('site:default');
if (in_array($_REQUEST['action'], $validActions)) {
    $version = $modx->getVersionData();
    if (version_compare($version['full_version'], '2.1.1-pl') >= 0) {
        if ($modx->user->hasSessionContext($modx->context->get('key'))) {
            $_SERVER['HTTP_MODAUTH'] = $_SESSION["modx.{$modx->context->get('key')}.user.token"];
        } else {
            $_SESSION["modx.{$modx->context->get('key')}.user.token"] = 0;
            $_SERVER['HTTP_MODAUTH'] = 0;
        }
    } else {
        $_SERVER['HTTP_MODAUTH'] = $modx->site_id;
    }
    $_REQUEST['HTTP_MODAUTH'] = $_SERVER['HTTP_MODAUTH'];
}

/* 
 * Handle request.
 */
$connectorRequestClass              = $modx->getOption('modConnectorRequest.class', null, 'modConnectorRequest');
$modx->config['modRequest.class']   = $connectorRequestClass;
$connectorResponseClass             = $modx->getOption('modConnectorResponse.class', null, 'modConnectorResponse');
$modx->config['modResponse.class']  = $connectorResponseClass;
$path                               = $modx->getOption(
    'processorsPath',
    $modx->site->config,
    $corePath . 'processors/'
);

$modx->getRequest();
$modx->request->sanitizeRequest();

$modx->request->handleRequest([
    'processors_path' => $path,
    'location' => '',
]);
