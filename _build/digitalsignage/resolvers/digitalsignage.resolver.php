<?php

$package = 'DigitalSignage';

$success = false;

/**
 * The needed permissions.
 */
$permissions = [
    [
        'name'          => 'digitalsignage',
        'description'   => 'To view the DigitalSignage package.',
        'templates'     => ['AdministratorTemplate']
    ],
    [
        'name'          => 'digitalsignage_settings',
        'description'   => 'To view/edit DigitalSignage package settings.',
        'templates'     => ['AdministratorTemplate'],
        'policies'      => ['Administrator']
    ]
];

/**
 * The needed settings.
 */
$settings = [
    'context'           => [
        'xtype'             => 'modx-combo-context'
    ],
    'request_resource'  => [],
    'export_resource'   => [],
    'templates'         => []
];

/**
 * The needed contexts.
 */
$contexts = [
    [
        'key'   => 'ds',
        'name'  => 'Digital Signage',
        'settings'      => [
            'base_url'      => [
                'value'         => '/ds/',
            ],
            'site_status'   => [
                'value'         => '1'
            ],
            'site_url'      => [
                'value'         => 'http://{http_host}/ds/'
            ],
            'mgr_tree_icon_context' => [
                'value'         => 'icon-play-circle',
                'area'          => 'manager'
            ]
        ]
    ]
];

/**
 * The needed templates.
 */
$templates = [
    [
        'templatename'  => 'Digital Signage (1.1.3-pl original)',
        'description'   => 'Digital Signage template for MODx Revolution',
        'icon'          => 'icon-play-circle',
        'content'       => file_get_contents(MODX_CORE_PATH . '/components/digitalsignage/elements/templates/digitalsignage.template.tpl'),
        'setting'       => 'templates'
    ]
];

/**
 * The needed resources.
 */
$resources = [
    [
        'pagetitle'     => 'Home',
        'content'       => '',
        'content_type'  => 7,
        'uri'           => 'home.json',
        'uri_override'  => 1,
        'setting'       => 'request_resource'
    ],
    [
        'pagetitle'     => 'Export',
        'content'       => '[[!DigitalSignage]]',
        'content_type'  => 7,
        'uri'           => 'export.json',
        'uri_override'  => 1,
        'setting'       => 'export_resource'
    ],
    [
        'pagetitle'     => 'Export (feed)',
        'content'       => '[[!DigitalSignageFeedExport]]',
        'content_type'  => 7,
        'uri'           => 'feed-export.json',
        'uri_override'  => 1
    ]
];

/**
 * The default slide types.
 */
$slides = [
    'default'       => [
        'data'          => 'a:3:{s:5:"image";a:5:{s:5:"xtype";s:18:"modx-combo-browser";s:13:"default_value";s:0:"";s:5:"label";s:0:"";s:11:"description";s:0:"";s:9:"menuindex";i:1;}s:6:"ticker";a:5:{s:5:"xtype";s:8:"checkbox";s:13:"default_value";s:1:"1";s:5:"label";s:0:"";s:11:"description";s:0:"";s:9:"menuindex";i:2;}s:7:"content";a:5:{s:5:"xtype";s:8:"richtext";s:13:"default_value";s:0:"";s:5:"label";s:0:"";s:11:"description";s:0:"";s:9:"menuindex";i:0;}}'
    ],
    'media'         => [
        'icon'          => 'picture-o',
        'data'          => 'a:4:{s:5:"image";a:5:{s:5:"xtype";s:18:"modx-combo-browser";s:13:"default_value";s:0:"";s:5:"label";s:0:"";s:11:"description";s:0:"";s:9:"menuindex";i:0;}s:7:"youtube";a:5:{s:5:"xtype";s:9:"textfield";s:13:"default_value";s:0:"";s:5:"label";s:0:"";s:11:"description";s:0:"";s:9:"menuindex";i:1;}s:10:"fullscreen";a:5:{s:5:"xtype";s:8:"checkbox";s:13:"default_value";s:1:"1";s:5:"label";s:0:"";s:11:"description";s:0:"";s:9:"menuindex";i:2;}s:6:"ticker";a:5:{s:5:"xtype";s:8:"checkbox";s:13:"default_value";s:1:"1";s:5:"label";s:0:"";s:11:"description";s:0:"";s:9:"menuindex";i:3;}}'
    ],
    'buienradar'    => [
        'icon'          => 'cloud',
        'data'          => 'a:3:{s:8:"location";a:6:{s:5:"xtype";s:9:"textfield";s:13:"default_value";s:0:"";s:5:"label";s:0:"";s:11:"description";s:0:"";s:5:"value";s:0:"";s:9:"menuindex";i:0;}s:10:"fullscreen";a:5:{s:5:"xtype";s:8:"checkbox";s:13:"default_value";s:1:"1";s:5:"label";s:0:"";s:11:"description";s:0:"";s:9:"menuindex";i:1;}s:6:"ticker";a:5:{s:5:"xtype";s:8:"checkbox";s:13:"default_value";s:1:"1";s:5:"label";s:0:"";s:11:"description";s:0:"";s:9:"menuindex";i:2;}}'
    ],
    'feed'          => array(
        'icon'          => 'rss',
        'data'          => 'a:4:{s:3:"url";a:5:{s:5:"xtype";s:9:"textfield";s:13:"default_value";s:0:"";s:5:"label";s:0:"";s:11:"description";s:0:"";s:9:"menuindex";i:0;}s:5:"limit";a:5:{s:5:"xtype";s:5:"combo";s:13:"default_value";s:22:"3 items==3||6 items==6";s:5:"label";s:0:"";s:11:"description";s:0:"";s:9:"menuindex";i:1;}s:10:"fullscreen";a:5:{s:5:"xtype";s:8:"checkbox";s:13:"default_value";s:1:"1";s:5:"label";s:0:"";s:11:"description";s:0:"";s:9:"menuindex";i:2;}s:6:"ticker";a:5:{s:5:"xtype";s:8:"checkbox";s:13:"default_value";s:1:"1";s:5:"label";s:0:"";s:11:"description";s:0:"";s:9:"menuindex";i:3;}}'
    )
];

switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
    case xPDOTransport::ACTION_UPGRADE:
        /**
         * Get the needed settings and set the default value,
         */
        foreach ($settings as $key => $setting) {
            $settings[$key] = array_merge([
                'key'       => strtolower($package).'.'.$key,
                'namespace' => strtolower($package),
                'area'      => strtolower($package)
            ], $setting);

            if (null !== ($settingObject = $object->xpdo->getObject('modSystemSetting', $settings[$key]['key']))) {
                $settings[$key]['value'] = $settingObject->get('value');
            } else {
                if (!isset($setting[$key]['value'])) {
                    $setting[$key]['value'] = '';
                }
            }
        }

        /**
         * Add the needed permissions.
         */
        foreach ($object->xpdo->getCollection('modAccessPolicyTemplate') as $templateObject) {
            foreach ($permissions as $permission) {
                if (!isset($permission['templates']) || in_array($templateObject->get('name'), $permission['templates'])) {
                    $permission = array_merge($permission, [
                        'template'  => $templateObject->get('id'),
                        'value'     => 1
                    ]);

                    $c = array(
                        'name'      => $permission['name'],
                        'template'  => $permission['template']
                    );

                    if (null === $object->xpdo->getObject('modAccessPermission', $c)) {
                        if (null !== ($permissionObject = $object->xpdo->newObject('modAccessPermission'))) {
                            $permissionObject->fromArray($permission);
                            $permissionObject->save();
                        }
                    }
                }
            }
        }

        foreach ($object->xpdo->getCollection('modAccessPolicy') as $policyObject) {
            $data = $policyObject->get('data');

            foreach ($permissions as $permission) {
                if (isset($permission['policies'])) {
                    if (in_array($policyObject->get('name'), $permission['policies'])) {
                        $data[$permission['name']] = true;
                    } else {
                        $data[$permission['name']] = false;
                    }
                } else {
                    $data[$permission['name']] = true;
                }
            }

            $policyObject->set('data', $data);
            $policyObject->save();
        }

        /**
         * Install the needed context and set the default settings.
         */
        foreach ($contexts as $context) {
            if (isset($context['key'])) {
                $c = [
                    'key' => $context['key']
                ];

                if (null === ($contextObject = $object->xpdo->getObject('modContext', $c))) {
                    if (null !== ($contextObject = $object->xpdo->newObject('modContext'))) {
                        $contextObject->fromArray($context, '', true, true);
                        $contextObject->save();

                        if (isset($context['settings'])) {
                            foreach ($context['settings'] as $contextKey => $contextSetting) {
                                $contextSetting = array_merge([
                                    'context_key'   => $contextObject->get('key'),
                                    'key'           => $contextKey,
                                    'namespace'     => 'core',
                                    'area'          => 'core'
                                ], $contextSetting);

                                if (null !== ($contextSettingObject = $object->xpdo->newObject('modContextSetting'))) {
                                    $contextSettingObject->fromArray($contextSetting, '', true, true);
                                    $contextSettingObject->save();
                                }
                            }
                        }

                        if (isset($settings['context'])) {
                            $settings['context']['value'] = $contextObject->get('key');
                        }
                    } else {
                        $object->xpdo->log(xPDO::LOG_LEVEL_ERROR, $context['name'] . ' context could not be created.');
                    }
                } else {
                    if (isset($settings['context'])) {
                        $settings['context']['value'] = $contextObject->get('key');
                    }
                }
            }
        }

        /**
         * Install the needed templates.
         */
        foreach ($templates as $template) {
            $c = [
                'templatename' => $template['templatename']
            ];

            if (null === ($templateObject = $object->xpdo->getObject('modTemplate', $c))) {
                if (null !== ($templateObject = $object->xpdo->newObject('modTemplate'))) {
                    $templateObject->fromArray($template, '', true, true);
                    $templateObject->save();

                    if (isset($template['setting'])) {
                        if (isset($settings[$template['setting']])) {
                            $currentValue = explode(',', $settings[$template['setting']]['value']);

                            $settings[$template['setting']]['value'] = implode(',', array_filter(array_merge($currentValue, [$templateObject->get('id')])));
                        }
                    }
                } else {
                    $object->xpdo->log(xPDO::LOG_LEVEL_ERROR, $template['templatename'] . ' template could not be created.');
                }
            } else {
                if (isset($template['setting'])) {
                    if (isset($settings[$template['setting']])) {
                        $currentValue = explode(',', $settings[$template['setting']]['value']);

                        $settings[$template['setting']]['value'] = implode(',', array_filter(array_merge($currentValue, [$templateObject->get('id')])));
                    }
                }
            }
        }

        /**
         * Install the needed resources.
         */
        foreach ($resources as $key => $resource) {
            $resource = array_merge([
                'context_key'   => $settings['context']['value'],
                'published'     => 1,
                'deleted'       => 0,
                'hidemenu'      => 0,
                'richtext'      => 0,
                'cacheable'     => 0,
                'template'      => '',
                'menuindex'     => $key
            ], $resource);

            $c = [
                'context_key'   => $resource['context_key'],
                'uri'           => $resource['uri']
            ];

            if (null === ($resourceObject = $object->xpdo->getObject('modResource', $c))) {
                if (null !== ($resourceObject = $object->xpdo->newObject('modResource'))) {
                    $resourceObject->fromArray($resource, '', true, true);
                    $resourceObject->save();

                    if (isset($resource['setting'])) {
                        if (isset($settings[$resource['setting']])) {
                            $settings[$resource['setting']]['value'] = $resourceObject->get('id');
                        }
                    }
                } else {
                    $object->xpdo->log(xPDO::LOG_LEVEL_ERROR, $resource['pagetitle'] . ' resource could not be created.');
                }
            } else {
                if (isset($resource['setting'])) {
                    if (isset($settings[$resource['setting']])) {
                        $settings[$resource['setting']]['value'] = $resourceObject->get('id');
                    }
                }
            }
        }

        /**
         * Install the needed settings and set it to the new value.
         */
        foreach ($settings as $key => $setting) {
            if (isset($options[$key])) {
                $setting['value'] = $options[$key];
            }

            $setting = array_merge([
                'key' => $key
            ], $setting);

            $c = [
                'key' => $setting['key']
            ];

            if (null === ($settingObject = $object->xpdo->getObject('modSystemSetting', $c))) {
                if (null !== ($settingObject = $object->xpdo->newObject('modSystemSetting'))) {
                    $settingObject->fromArray($setting, '', true, true);
                    $settingObject->save();
                } else {
                    $object->xpdo->log(xPDO::LOG_LEVEL_ERROR, $setting['key'] . ' setting could not be created.');
                }
            } else {
                $settingObject->fromArray($setting, '', true, true);
                $settingObject->save();
            }
        }

        /**
         * The default slide types.
         */
        if ($object->xpdo->loadClass('DigitalSignage', $object->xpdo->getOption('digitalsignage.core_path', null, $object->xpdo->getOption('core_path').'components/digitalsignage/').'model/digitalsignage/', true, true)) {
            $digitalsignage = new DigitalSignage($modx);

            foreach ($slides as $key => $slide) {
                $slide = array_merge([
                    'key'   => $key,
                    'time'  => 10
                ], $slide);

                $c = [
                    'key' => $slide['key']
                ];

                if (null === ($slideObject = $object->xpdo->getObject('DigitalSignageSlidesTypes', $c))) {
                    if (null !== ($slideObject = $object->xpdo->newObject('DigitalSignageSlidesTypes'))) {
                        $slideObject->fromArray($slide, '', true, true);
                        $slideObject->save();
                    } else {
                        $object->xpdo->log(xPDO::LOG_LEVEL_ERROR, $slide['key'] . ' slide type could not be created.');
                    }
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