<?php
    if ($modx->loadClass('Facebook', $modx->getOption('facebook.core_path', null, $modx->getOption('core_path').'components/facebook/').'model/facebook/', true, true)) {
        $facebook = new Facebook($modx);

        if ($facebook instanceof Facebook) {
            return $facebook->run($scriptProperties);
        }
    }