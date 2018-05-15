<?php
// The package provider in MODX, that connected to modstore.pro with key of owner of this package store

define('PKG_PROVIDER_ID', 2);

require dirname(__FILE__) . '/packagebuilder.class.php';
$package = new PackageBuilder(
    dirname(__FILE__) . '/package.json',
    dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/core/'
);

if ($provider = $package->modx->getObject('transport.modTransportProvider', PKG_PROVIDER_ID)) {
    $package->loadPackage();
    $package->modx->setOption('contentType', 'default');
    $params = array(
        'package' => $package->package['signature'],
        'version' => $package->package['version'],
        'username' => $provider->username,
        'api_key' => $provider->api_key,
        'vehicle_version' => '2.0.0',
    );

    /** @var modRestResponse $response */
    $response = $provider->request('package/encode', 'POST', $params);
    if ($response->isError()) {
        $msg = $response->getError();
        $package->modx->log(modX::LOG_LEVEL_ERROR, $msg);
    } else {
        $data = $response->toXml();
        if (!empty($data->key)) {
            define('PKG_ENCODE_KEY', $data->key);
        } elseif (!empty($data->message)) {
            $package->modx->log(modX::LOG_LEVEL_ERROR, $data->message);
        }
    }
}

$package->createPackageZip();
