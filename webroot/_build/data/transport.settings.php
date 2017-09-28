<?php

    $settings = array();

    $settings[0] = $modx->newObject('modSystemSetting');
    $settings[0]->fromArray(array(
        'key' 		=> PKG_NAME_LOWER.'.branding_url',
        'value' 	=> 'http://www.sterc.com',
        'xtype' 	=> 'textfield',
        'namespace' => PKG_NAME_LOWER,
        'area' 		=> PKG_NAME_LOWER
    ), '', true, true);

    $settings[1] = $modx->newObject('modSystemSetting');
    $settings[1]->fromArray(array(
        'key' 		=> PKG_NAME_LOWER.'.branding_url_help',
        'value' 	=> '//www.sterc.com/extras/narrowcasting',
        'xtype' 	=> 'textfield',
        'namespace' => PKG_NAME_LOWER,
        'area' 		=> PKG_NAME_LOWER
    ), '', true, true);

    $settings[2] = $modx->newObject('modSystemSetting');
    $settings[2]->fromArray(array(
            'key' 		=> PKG_NAME_LOWER.'.auto_create_sync',
            'value' 	=> 'true',
            'xtype' 	=> 'combo-boolean',
            'namespace' => PKG_NAME_LOWER,
            'area' 		=> PKG_NAME_LOWER
    ), '', true, true);

    $settings[3] = $modx->newObject('modSystemSetting');
    $settings[3]->fromArray(array(
        'key' 		=> PKG_NAME_LOWER.'.context',
        'value' 	=> 'nc',
        'xtype' 	=> 'modx-combo-context',
        'namespace' => PKG_NAME_LOWER,
        'area' 		=> PKG_NAME_LOWER
    ), '', true, true);

    $settings[4] = $modx->newObject('modSystemSetting');
    $settings[4]->fromArray(array(
        'key' 		=> PKG_NAME_LOWER.'.export_resource',
        'value' 	=> '',
        'xtype' 	=> 'textfield',
        'namespace' => PKG_NAME_LOWER,
        'area' 		=> PKG_NAME_LOWER
    ), '', true, true);

    $settings[5] = $modx->newObject('modSystemSetting');
    $settings[5]->fromArray(array(
        'key' 		=> PKG_NAME_LOWER.'.media_source',
        'value' 	=> '1',
        'xtype' 	=> 'modx-combo-source',
        'namespace' => PKG_NAME_LOWER,
        'area' 		=> PKG_NAME_LOWER
    ), '', true, true);

    $settings[6] = $modx->newObject('modSystemSetting');
    $settings[6]->fromArray(array(
        'key' 		=> PKG_NAME_LOWER.'.request_param_broadcast',
        'value' 	=> 'bc',
        'xtype' 	=> 'textfield',
        'namespace' => PKG_NAME_LOWER,
        'area' 		=> PKG_NAME_LOWER
    ), '', true, true);

    $settings[7] = $modx->newObject('modSystemSetting');
    $settings[7]->fromArray(array(
        'key' 		=> PKG_NAME_LOWER.'.request_param_player',
        'value' 	=> 'pl',
        'xtype' 	=> 'textfield',
        'namespace' => PKG_NAME_LOWER,
        'area' 		=> PKG_NAME_LOWER
    ), '', true, true);

    $settings[8] = $modx->newObject('modSystemSetting');
    $settings[8]->fromArray(array(
        'key' 		=> PKG_NAME_LOWER.'.request_resource',
        'value' 	=> '',
        'xtype' 	=> 'textfield',
        'namespace' => PKG_NAME_LOWER,
        'area' 		=> PKG_NAME_LOWER
    ), '', true, true);

    $settings[9] = $modx->newObject('modSystemSetting');
    $settings[9]->fromArray(array(
        'key' 		=> PKG_NAME_LOWER.'.templates',
        'value' 	=> '',
        'xtype' 	=> 'textfield',
        'namespace' => PKG_NAME_LOWER,
        'area' 		=> PKG_NAME_LOWER
    ), '', true, true);

    return $settings;

?>