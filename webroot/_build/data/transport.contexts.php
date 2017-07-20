<?php

    $contexts = array();

    foreach (array('nc' => 'Narrowcasting') as $name => $value) {
        $contexts[$name] = $modx->newObject('modContext');
        $contexts[$name]->fromArray(array(
                'key'         => 'nc',
                'name'        => 'Narrowcasting',
                'description' => PKG_NAME . ' ' . PKG_VERSION . '-' . PKG_RELEASE . ' context for MODx Revolution'
        ), '', true, true);

        if (file_exists(__DIR__ . '/settings/' . $name . '.settings.php')) {
            $settings = array();

            foreach (include_once __DIR__ . '/settings/' . $name . '.settings.php' as $key => $value) {
                $settings[$key] = $modx->newObject('modContextSetting');
                $settings[$key]->fromArray($value, '', true, true);
            }

            $contexts[$name]->addMany($settings);
        }
    }

return $contexts;

?>