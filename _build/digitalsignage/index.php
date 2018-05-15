<?php

    /**
     * Package Builder
     *
     * Copyright 2017 by Oene Tjeerd de Bruin <modx@oetzie.nl>
     */

    require_once __DIR__ . '/packagebuilder.class.php';

    $package = new PackageBuilder(
        __DIR__ . '/package.json',
        dirname(dirname(__DIR__)) . '/core/'
    );

    $package->createPackageZip();

?>