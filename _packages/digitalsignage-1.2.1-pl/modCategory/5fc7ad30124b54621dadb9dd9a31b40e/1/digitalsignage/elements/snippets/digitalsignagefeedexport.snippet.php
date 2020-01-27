<?php
/**
 * Digital Signage
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
 */

$instance = $modx->getService('digitalsignagereadfeeds', 'DigitalSignageReadFeeds', $modx->getOption('digitalsignage.core_path', null, $modx->getOption('core_path') . 'components/digitalsignage/') . 'model/digitalsignage/');

if ($instance instanceof DigitalSignageReadFeeds) {
    return $instance->readDigitalSignageFeeds($scriptProperties);
}

return json_encode([
   'items' => []
]);