<?php

    if ($modx->loadClass('DigitalSignageSocialMedia', $modx->getOption('digitalsignagesocialmedia.core_path', null, $modx->getOption('core_path').'components/digitalsignagesocialmedia/').'model/digitalsignagesocialmedia/', true, true)) {
        $digitalSignageSocialMedia = new DigitalSignageSocialMedia($modx);

        if ($digitalSignageSocialMedia instanceof DigitalSignageSocialMedia) {
            return $digitalSignageSocialMedia->run($scriptProperties);
        }
    }

?>