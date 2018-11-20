<?php

    /**
     * Package Builder
     *
     * Copyright 2017 by Oene Tjeerd de Bruin <modx@oetzie.nl>
     */

    require_once __DIR__ . '/packagebuilder.class.php';
    if (!defined('MODX_CORE_PATH')) {
        require_once dirname(dirname(__DIR__)) . '/config.core.php';
    }

    $modx = isset($modx) ? $modx : null;
    $package = new PackageBuilder(
        __DIR__ . '/package.json',
        dirname(dirname(__DIR__)) . '/core/',
        null,
        $modx
    );

    $package->createPackageZip();

?>