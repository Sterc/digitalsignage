<?php
/**
 * StatusOverrideIPs Connector
 *
 * @package statusoverrideips
 */
require_once dirname(dirname(dirname(dirname(__FILE__)))).'/config.core.php';
require_once MODX_CORE_PATH.'config/'.MODX_CONFIG_KEY.'.inc.php';
require_once MODX_CONNECTORS_PATH.'index.php';

$corePath = $modx->getOption('statusoverrideips.core_path',null,$modx->getOption('core_path').'components/statusoverrideips/');
require_once $corePath.'model/statusoverrideips/statusoverrideips.class.php';
$modx->statusoverrideips = new StatusOverrideIPs($modx);

$modx->lexicon->load('statusoverrideips:default');

/* handle request */
$path = $modx->getOption('processorsPath',$modx->statusoverrideips->config,$corePath.'processors/');
$modx->request->handleRequest(array(
    'processors_path' => $path,
    'location' => '',
));