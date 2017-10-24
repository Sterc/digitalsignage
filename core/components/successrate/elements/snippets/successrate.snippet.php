<?php

    if ($modx->loadClass('SuccessRate', $modx->getOption('successrate.core_path', null, $modx->getOption('core_path').'components/successrate/').'model/successrate/', true, true)) {
        $successRate = new SuccessRate($modx);

        if ($successRate instanceof SuccessRate) {
            return $successRate->run($scriptProperties);
        }
    }