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

    $package = new PackageBuilder(
        __DIR__ . '/package.json',
        MODX_CORE_PATH
    );

    $package->createPackageZip();

?>