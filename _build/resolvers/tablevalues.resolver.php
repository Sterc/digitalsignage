<?php

/**
 * Digital Signage
 *
 * Copyright 2019 by Sterc <modx@sterc.nl>
 */

$package = 'DigitalSignage';

$settings = [];

$contexts = [[
    'key'       => 'ds',
    'name'      => 'Digital Signage',
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
]];

$resources = [[
    'pagetitle'     => 'Home',
    'content'       => '',
    'content_type'  => '.json',
    'uri'           => 'home.json',
    'uri_override'  => 1,
    'setting'       => 'request_resource'
], [
    'pagetitle'     => 'Export',
    'content'       => '[[!DigitalSignage]]',
    'content_type'  => '.json',
    'uri'           => 'export.json',
    'uri_override'  => 1,
    'setting'       => 'export_resource'
], [
    'pagetitle'     => 'Export (feed)',
    'content'       => '[[!DigitalSignageFeedExport]]',
    'content_type'  => '.json',
    'uri'           => 'feed-export.json',
    'uri_override'  => 1,
    'setting'       => 'export_feed_resource'
]];

$slidetypes = [[
    'key'           => 'default',
    'time'          => '10',
    'data'          => 'a:4:{s:5:"image";a:7:{s:5:"xtype";s:18:"modx-combo-browser";s:13:"default_value";s:0:"";s:5:"label";s:0:"";s:11:"description";s:0:"";s:9:"menuindex";i:1;s:8:"required";N;s:6:"values";s:0:"";}s:6:"ticker";a:7:{s:5:"xtype";s:8:"checkbox";s:13:"default_value";s:1:"1";s:5:"label";s:0:"";s:11:"description";s:0:"";s:9:"menuindex";i:2;s:8:"required";N;s:6:"values";s:0:"";}s:7:"content";a:5:{s:5:"xtype";s:8:"richtext";s:13:"default_value";s:0:"";s:5:"label";s:0:"";s:11:"description";s:0:"";s:9:"menuindex";i:0;}s:10:"fullscreen";a:7:{s:5:"xtype";s:8:"checkbox";s:8:"required";N;s:6:"values";s:0:"";s:13:"default_value";s:1:"1";s:5:"label";s:0:"";s:11:"description";s:0:"";s:9:"menuindex";i:3;}}'
], [
    'key'           => 'media',
    'icon'          => 'picture-o',
    'time'          => '10',
    'data'          => 'a:5:{s:5:"image";a:5:{s:5:"xtype";s:18:"modx-combo-browser";s:13:"default_value";s:0:"";s:5:"label";s:0:"";s:11:"description";s:0:"";s:9:"menuindex";i:0;}s:10:"fullscreen";a:5:{s:5:"xtype";s:8:"checkbox";s:13:"default_value";s:1:"1";s:5:"label";s:0:"";s:11:"description";s:0:"";s:9:"menuindex";i:4;}s:6:"ticker";a:5:{s:5:"xtype";s:8:"checkbox";s:13:"default_value";s:1:"1";s:5:"label";s:0:"";s:11:"description";s:0:"";s:9:"menuindex";i:3;}s:12:"video_extern";a:7:{s:5:"xtype";s:9:"textfield";s:8:"required";N;s:6:"values";s:0:"";s:13:"default_value";s:0:"";s:5:"label";s:0:"";s:11:"description";s:0:"";s:9:"menuindex";i:1;}s:12:"video_intern";a:5:{s:5:"xtype";s:18:"modx-combo-browser";s:13:"default_value";s:0:"";s:5:"label";s:0:"";s:11:"description";s:0:"";s:9:"menuindex";i:2;}}'
], [
    'key'           => 'buienradar',
    'icon'          => 'cloud',
    'time'          => '10',
    'data'          => 'a:3:{s:8:"location";a:6:{s:5:"xtype";s:9:"textfield";s:13:"default_value";s:0:"";s:5:"label";s:0:"";s:11:"description";s:0:"";s:5:"value";s:0:"";s:9:"menuindex";i:0;}s:10:"fullscreen";a:5:{s:5:"xtype";s:8:"checkbox";s:13:"default_value";s:1:"1";s:5:"label";s:0:"";s:11:"description";s:0:"";s:9:"menuindex";i:2;}s:6:"ticker";a:5:{s:5:"xtype";s:8:"checkbox";s:13:"default_value";s:1:"1";s:5:"label";s:0:"";s:11:"description";s:0:"";s:9:"menuindex";i:1;}}'
], [
    'key'           => 'feed',
    'icon'          => 'rss',
    'time'          => '10',
    'data'          => 'a:4:{s:3:"url";a:5:{s:5:"xtype";s:9:"textfield";s:13:"default_value";s:0:"";s:5:"label";s:0:"";s:11:"description";s:0:"";s:9:"menuindex";i:0;}s:5:"limit";a:7:{s:5:"xtype";s:5:"combo";s:13:"default_value";s:1:"3";s:5:"label";s:0:"";s:11:"description";s:0:"";s:9:"menuindex";i:1;s:8:"required";s:1:"1";s:6:"values";s:22:"2 items==2||3 items==3";}s:10:"fullscreen";a:5:{s:5:"xtype";s:8:"checkbox";s:13:"default_value";s:1:"1";s:5:"label";s:0:"";s:11:"description";s:0:"";s:9:"menuindex";i:3;}s:6:"ticker";a:5:{s:5:"xtype";s:8:"checkbox";s:13:"default_value";s:1:"1";s:5:"label";s:0:"";s:11:"description";s:0:"";s:9:"menuindex";i:2;}}'
], [
    'key'           => 'payoff',
    'icon'          => 'bullhorn',
    'time'          => '10',
    'data'          => 'a:4:{s:7:"content";a:7:{s:5:"xtype";s:9:"textfield";s:8:"required";s:1:"1";s:6:"values";s:0:"";s:13:"default_value";s:0:"";s:5:"label";s:0:"";s:11:"description";s:0:"";s:9:"menuindex";i:1;}s:4:"size";a:7:{s:5:"xtype";s:5:"combo";s:8:"required";s:1:"1";s:6:"values";s:61:"Standaard==size1||Groot==size2||Groter==size3||Grootst==size4";s:13:"default_value";s:5:"size1";s:5:"label";s:0:"";s:11:"description";s:0:"";s:9:"menuindex";i:2;}s:6:"ticker";a:7:{s:5:"xtype";s:8:"checkbox";s:8:"required";N;s:6:"values";s:0:"";s:13:"default_value";s:1:"1";s:5:"label";s:0:"";s:11:"description";s:0:"";s:9:"menuindex";i:3;}s:10:"fullscreen";a:7:{s:5:"xtype";s:8:"checkbox";s:8:"required";N;s:6:"values";s:0:"";s:13:"default_value";s:1:"1";s:5:"label";s:0:"";s:11:"description";s:0:"";s:9:"menuindex";i:4;}}'
], [
    'key'           => 'countdown',
    'icon'          => 'calendar',
    'time'          => '10',
    'data'          => 'a:4:{s:7:"content";a:7:{s:5:"xtype";s:8:"richtext";s:8:"required";N;s:6:"values";s:0:"";s:13:"default_value";s:0:"";s:5:"label";s:0:"";s:11:"description";s:0:"";s:9:"menuindex";i:1;}s:4:"date";a:7:{s:5:"xtype";s:9:"xdatetime";s:8:"required";s:1:"1";s:6:"values";s:0:"";s:13:"default_value";s:0:"";s:5:"label";s:0:"";s:11:"description";s:0:"";s:9:"menuindex";i:2;}s:6:"ticker";a:7:{s:5:"xtype";s:8:"checkbox";s:8:"required";N;s:6:"values";s:0:"";s:13:"default_value";s:1:"1";s:5:"label";s:0:"";s:11:"description";s:0:"";s:9:"menuindex";i:3;}s:10:"fullscreen";a:7:{s:5:"xtype";s:8:"checkbox";s:8:"required";N;s:6:"values";s:0:"";s:13:"default_value";s:1:"1";s:5:"label";s:0:"";s:11:"description";s:0:"";s:9:"menuindex";i:4;}}'
], [
    'key'           => 'clock',
    'icon'          => 'clock-o',
    'time'          => '10',
    'data'          => 'a:2:{s:6:"ticker";a:7:{s:5:"xtype";s:8:"checkbox";s:8:"required";N;s:6:"values";s:0:"";s:13:"default_value";s:1:"1";s:5:"label";s:0:"";s:11:"description";s:0:"";s:9:"menuindex";i:1;}s:10:"fullscreen";a:7:{s:5:"xtype";s:8:"checkbox";s:8:"required";N;s:6:"values";s:0:"";s:13:"default_value";s:1:"1";s:5:"label";s:0:"";s:11:"description";s:0:"";s:9:"menuindex";i:2;}}'
], [
    'key'           => 'iframe',
    'icon'          => 'window-restore',
    'time'          => '10',
    'data'          => 'a:3:{s:3:"url";a:7:{s:5:"xtype";s:9:"textfield";s:8:"required";s:1:"1";s:6:"values";s:0:"";s:13:"default_value";s:0:"";s:5:"label";s:0:"";s:11:"description";s:0:"";s:9:"menuindex";i:1;}s:6:"ticker";a:7:{s:5:"xtype";s:8:"checkbox";s:8:"required";N;s:6:"values";s:0:"";s:13:"default_value";s:1:"1";s:5:"label";s:0:"";s:11:"description";s:0:"";s:9:"menuindex";i:2;}s:10:"fullscreen";a:7:{s:5:"xtype";s:8:"checkbox";s:8:"required";N;s:6:"values";s:0:"";s:13:"default_value";s:1:"1";s:5:"label";s:0:"";s:11:"description";s:0:"";s:9:"menuindex";i:3;}}'
]];

$success = false;

if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            $modx =& $object->xpdo;

            foreach ($contexts as $context) {
                if (isset($context['key'])) {
                    $settings['context'] = $context['key'];

                    $object = $modx->getObject('modContext', [
                        'key' => $context['key']
                    ]);

                    if (!$object) {
                        $object = $modx->newObject('modContext');
                    }

                    $object->fromArray($context, '', true, true);
                    $object->save();

                    if (isset($context['settings'])) {
                        foreach ((array) $context['settings'] as $key => $setting) {
                            $settingObject = $modx->getObject('modContextSetting', [
                                'context_key'   => $object->get('key'),
                                'key'           => $key,
                            ]);

                            if (!$settingObject) {
                                $settingObject = $modx->newObject('modContextSetting');

                                $settingObject->fromArray(array_merge([
                                    'context_key'   => $context['key'],
                                    'key'           => $key,
                                    'namespace'     => 'core',
                                    'area'          => 'core'
                                ], $setting), '', true, true);

                                $settingObject->save();
                            }
                        }
                    }
                }
            }

            if (isset($settings['context'])) {
                foreach ($resources as $key => $resource) {
                    $object = $modx->getObject('modResource', [
                        'context_key'   => $settings['context'],
                        'uri'           => $resource['uri']
                    ]);

                    if (!$object) {
                        $object = $modx->newObject('modResource');
                    }

                    if (isset($resource['content_type'])) {
                        $contentTypeObject = $modx->getObject('modContentType', [
                            'file_extensions' => $resource['content_type']
                        ]);

                        if ($contentTypeObject) {
                            $resource['content_type'] = $contentTypeObject->get('id');
                        } else {
                            unset($resource['content_type']);
                        }
                    }

                    $object->fromArray(array_merge([
                        'context_key'   => $settings['context'],
                        'published'     => 1,
                        'deleted'       => 0,
                        'hidemenu'      => 0,
                        'richtext'      => 0,
                        'cacheable'     => 0,
                        'template'      => '',
                        'menuindex'     => $key
                    ], $resource), '', true, true);

                    $object->save();

                    if (isset($resource['setting'])) {
                        $settings[$resource['setting']] = $object->get('id');
                    }
                }
            }


            foreach ($settings as $key => $setting) {
                $object = $modx->getObject('modSystemSetting', [
                    'key' => strtolower($package) . '.' . $key
                ]);

                if ($object) {
                    $object->fromArray([
                        'value' => $setting
                    ]);

                    $object->save();
                }
            }

            foreach ($slidetypes as $slideType) {
                if (isset($slideType['key'])) {
                    $object = $modx->getObject('DigitalSignageSlidesTypes', [
                        'key' => $slideType['key']
                    ]);

                    if (!$object) {
                        $object = $modx->newObject('DigitalSignageSlidesTypes');
                    }

                    $object->fromArray($slideType);
                    $object->save();
                }
            }

            $success = true;

            break;
        case xPDOTransport::ACTION_UNINSTALL:
            $success = true;

            break;
    }
}

return $success;
