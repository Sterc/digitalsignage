<?php

$package = 'DigitalSignage';

$success = false;

switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
    case xPDOTransport::ACTION_UPGRADE:
        $settings = [
            'user_name',
            'user_email'
        ];

        foreach ($settings as $key) {
            if (isset($options[$key])) {
                $c = array(
                    'key' => strtolower($package).'.'.$key
                );

                if (null === ($setting = $object->xpdo->getObject('modSystemSetting', $c))) {
                    $setting = $object->xpdo->newObject('modSystemSetting');
                }

                $setting->fromArray([
                    'value' => $options[$key],
                    'key'   => strtolower($package).'.'.$key
                ], '', true, true);

                $setting->save();
            }
        }

        $success = true;

        break;
    case xPDOTransport::ACTION_UNINSTALL:
        $success = true;

        break;
}

return $success;

?>