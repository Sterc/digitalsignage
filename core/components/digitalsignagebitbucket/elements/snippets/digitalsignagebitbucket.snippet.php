<?php

    if ($modx->loadClass('DigitalSignageBitbucket', $modx->getOption('digitalsignagebitbucket.core_path', null, $modx->getOption('core_path').'components/digitalsignagebitbucket/').'model/digitalsignagebitbucket/', true, true)) {
        $digitalSignageBitbucket = new DigitalSignageBitbucket($modx);

        if ($digitalSignageBitbucket instanceof DigitalSignageBitbucket) {
            return $digitalSignageBitbucket->run($scriptProperties);
        }
    }

?>