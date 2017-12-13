<?php

// Allow frontend usage of connector
if (isset($_REQUEST['action']) && substr($_REQUEST['action'], 0, 4) === 'web/') {
    define('MODX_REQP', false);

    $_SERVER['HTTP_MODAUTH']  = 0;
    $_REQUEST['HTTP_MODAUTH'] = 0;
    $_REQUEST['ctx']          = 'web';
}

require_once dirname(dirname(dirname(dirname(__FILE__)))).'/config.core.php';

require_once MODX_CORE_PATH.'config/'.MODX_CONFIG_KEY.'.inc.php';
require_once MODX_CONNECTORS_PATH.'index.php';

$instance = $modx->getService('digitalsignage', 'DigitalSignage', $modx->getOption('digitalsignage.core_path', null, $modx->getOption('core_path').'components/digitalsignage/').'model/digitalsignage/');

if ($instance instanceOf DigitalSignage) {
    $modx->request->handleRequest(array(
        'processors_path' => $instance->config['processors_path'],
        'location' => ''
    ));
}
