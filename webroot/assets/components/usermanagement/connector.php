<?php
/**
 * userManagement Connector
 *
 * @package usermanagement
 */
require_once dirname(dirname(dirname(dirname(__FILE__)))).'/config.core.php';
require_once MODX_CORE_PATH.'config/'.MODX_CONFIG_KEY.'.inc.php';
require_once MODX_CONNECTORS_PATH.'index.php';

$corePath = $modx->getOption('usermanagement.core_path',null,$modx->getOption('core_path').'components/usermanagement/');
require_once $corePath.'model/usermanagement/usermanagement.class.php';
$modx->usermanagement = new userManagement($modx);

$modx->lexicon->load('usermanagement:default');

/* handle request */
$path = $modx->getOption('processorsPath',$modx->usermanagement->config,$corePath.'processors/');
$modx->request->handleRequest(array(
    'processors_path' => $path,
    'location' => '',
));