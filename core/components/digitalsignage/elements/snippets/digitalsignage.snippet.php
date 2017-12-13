<?php

    if ($modx->loadClass('DigitalSignage', $modx->getOption('digitalsignage.core_path', null, $modx->getOption('core_path').'components/digitalsignage/').'model/digitalsignage/', true, true)) {
        $digitalsignage = new DigitalSignage($modx);

        if ($digitalsignage instanceOf DigitalSignage) {
            return $digitalsignage->initializeBroadcast($scriptProperties);
        }
    }

?>