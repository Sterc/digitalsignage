<?php

    switch($modx->event->name) {
        case 'OnHandleRequest':
            if ($modx->loadClass('DigitalSignage', $modx->getOption('digitalsignage.core_path', null, $modx->getOption('core_path').'components/digitalsignage/').'model/digitalsignage/', true, true)) {
                $digitalsignage = new DigitalSignage($modx);

                if ($digitalsignage instanceOf DigitalSignage) {
                    $digitalsignage->initializeContext($scriptProperties);
                }
            }

            break;
        case 'OnLoadWebDocument':
        case 'OnWebPagePrerender':
            if ($modx->loadClass('DigitalSignage', $modx->getOption('digitalsignage.core_path', null, $modx->getOption('core_path').'components/digitalsignage/').'model/digitalsignage/', true, true)) {
                $digitalsignage = new DigitalSignage($modx);

                if ($digitalsignage instanceOf DigitalSignage) {
                    $digitalsignage->initializePlayer($scriptProperties);
                }
            }

            break;
    }

    return;