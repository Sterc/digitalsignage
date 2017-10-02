<?php

    $package = 'Narrowcasting';

    $success = false;

    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            $settings = array(
                'user_name',
                'user_email'
            );

            foreach ($settings as $key) {
                if (isset($options[$key])) {
                    $c = array(
                        'key' => strtolower($package) . '.' . $key
                    );

                    if (null !== ($setting = $object->xpdo->getObject('modSystemSetting', $c))) {
                        $setting->set('value', $options[$key]);

                        $setting->save();
                    } else {
                        $object->xpdo->log(xPDO::LOG_LEVEL_ERROR, '[' . $package . '] ' . strtolower($package) . '.' . $key . ' setting could not be found, so the setting could not be changed.');
                    }
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