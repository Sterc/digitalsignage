<?php
/**
 * Digital Signage
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
 */

$instance = $modx->getService('digitalsignage', 'DigitalSignage', $modx->getOption('digitalsignage.core_path', null, $modx->getOption('core_path') . 'components/digitalsignage/') . 'model/digitalsignage/');

if ($instance instanceof DigitalSignage) {
    return $instance->initializeBroadcast($scriptProperties);
}