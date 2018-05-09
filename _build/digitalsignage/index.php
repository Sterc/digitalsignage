<?php

    /**
     * Package Builder
     *
     * Copyright 2017 by Oene Tjeerd de Bruin <modx@oetzie.nl>
     */

    require_once __DIR__ . '/packagebuilder.class.php';

    $package = new PackageBuilder();

    $package->createPackageZip();

?>