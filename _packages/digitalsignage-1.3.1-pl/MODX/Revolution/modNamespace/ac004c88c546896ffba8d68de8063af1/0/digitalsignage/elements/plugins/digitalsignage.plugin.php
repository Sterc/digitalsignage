<?php
/**
 * Digital Signage
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
 */

switch($modx->event->name) {
    case 'OnHandleRequest':
        $instance = $modx->getService('digitalsignage', 'DigitalSignage', $modx->getOption('digitalsignage.core_path', null, $modx->getOption('core_path') . 'components/digitalsignage/') . 'model/digitalsignage/');

        if ($instance instanceOf DigitalSignage) {
            $instance->initializeContext($scriptProperties);
        }

        break;
    case 'OnLoadWebDocument':
    case 'OnWebPagePrerender':
        $instance = $modx->getService('digitalsignage', 'DigitalSignage', $modx->getOption('digitalsignage.core_path', null, $modx->getOption('core_path') . 'components/digitalsignage/') . 'model/digitalsignage/');

        if ($instance instanceOf DigitalSignage) {
            $instance->initializePlayer($scriptProperties);
        }

        break;
}

return;