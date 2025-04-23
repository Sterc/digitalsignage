<?php

    require_once dirname(dirname(dirname(__DIR__))) . '/config.core.php';

    require_once MODX_CORE_PATH . 'config/'.MODX_CONFIG_KEY . '.inc.php';
    require_once MODX_CONNECTORS_PATH . 'index.php';

    $modx->getService('digitalsignage', 'DigitalSignage', $modx->getOption('digitalsignage.core_path', null, $modx->getOption('core_path') . 'components/digitalsignage/') . 'model/digitalsignage/');

    if ($modx->digitalsignage instanceof DigitalSignage) {
        $modx->request->handleRequest([
            'processors_path'   => $modx->digitalsignage->config['processors_path'],
            'location'          => ''
        ]);
    }

?>