<?php

use MODX\Revolution\modAccessPermission;
use MODX\Revolution\modAccessPolicy;
use MODX\Revolution\modAccessPolicyTemplate;
use xPDO\Transport\xPDOTransport;

return function ($modx, $action) {
    if ($action === xPDOTransport::ACTION_UNINSTALL) {
        return true;
    }

    $permissions = [[
        'name'          => 'digitalsignage',
        'description'   => 'To view the digital signage package.',
        'templates'     => ['AdministratorTemplate']
    ], [
        'name'          => 'digitalsignage_admin',
        'description'   => 'To view the digital signage package, admin part.',
        'templates'     => ['AdministratorTemplate'],
        'policies'      => ['Administrator']
    ]];

    foreach ($modx->getCollection(modAccessPolicyTemplate::class) as $accessTemplate) {
        foreach ($permissions as $permission) {
            if (!isset($permission['templates']) || in_array($accessTemplate->get('name'), $permission['templates'])) {
                $accessPermission = $modx->getObject(modAccessPermission::class, [
                    'name'      => $permission['name'],
                    'template'  => $accessTemplate->get('id')
                ]);

                if (!$accessPermission) {
                    $accessPermission = $modx->newObject(modAccessPermission::class);

                    $accessPermission->fromArray(array_merge($permission, [
                        'template'  => $accessTemplate->get('id'),
                        'value'     => 1
                    ]));

                    $accessPermission->save();
                }
            }
        }
    }

    foreach ($modx->getCollection(modAccessPolicy::class) as $accessPolicy) {
        $data = $accessPolicy->get('data');

        foreach ($permissions as $permission) {
            if (isset($data[$permission['name']])) {
                continue;
            }

            if (isset($permission['policies'])) {
                $data[$permission['name']] = in_array($accessPolicy->get('name'), $permission['policies'], true);
            } else {
                $data[$permission['name']] = true;
            }
        }

        $accessPolicy->set('data', $data);
        $accessPolicy->save();
    }

    return true;
};
