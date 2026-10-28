<?php

use MODX\Revolution\modContentType;
use MODX\Revolution\modContext;
use MODX\Revolution\modContextSetting;
use MODX\Revolution\modResource;
use MODX\Revolution\modSystemSetting;
use xPDO\Transport\xPDOTransport;

return function ($modx, $action) {
    if ($action === xPDOTransport::ACTION_UNINSTALL) {
        return true;
    }

    $modx->addPackage('digitalsignage', $modx->getOption('digitalsignage.core_path', null, $modx->getOption('core_path') . 'components/digitalsignage/') . 'model/');

    $context = [
        'key'       => 'ds',
        'name'      => 'Digital Signage',
        'settings'  => [
            'base_url'              => ['value' => '/ds/'],
            'site_status'           => ['value' => '1'],
            'site_url'              => ['value' => 'http://{http_host}/ds/'],
            'mgr_tree_icon_context' => ['value' => 'icon-play-circle', 'area' => 'manager']
        ]
    ];

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

    $slideTypes = [[
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

    $settings = ['context' => $context['key']];

    $contextObject = $modx->getObject(modContext::class, ['key' => $context['key']]);

    if (!$contextObject) {
        $contextObject = $modx->newObject(modContext::class);
        $contextObject->fromArray(['key' => $context['key'], 'name' => $context['name']], '', true, true);
        $contextObject->save();
    }

    foreach ($context['settings'] as $key => $setting) {
        $exists = $modx->getObject(modContextSetting::class, [
            'context_key'   => $context['key'],
            'key'           => $key,
        ]);

        if (!$exists) {
            $settingObject = $modx->newObject(modContextSetting::class);
            $settingObject->fromArray(array_merge([
                'context_key'   => $context['key'],
                'key'           => $key,
                'namespace'     => 'core',
                'area'          => 'core'
            ], $setting), '', true, true);
            $settingObject->save();
        }
    }

    foreach ($resources as $key => $resource) {
        $object = $modx->getObject(modResource::class, [
            'context_key'   => $context['key'],
            'uri'           => $resource['uri']
        ]);

        if (!$object) {
            $contentType = $modx->getObject(modContentType::class, ['file_extensions' => $resource['content_type']]);

            $object = $modx->newObject(modResource::class);
            $object->fromArray(array_merge([
                'context_key'   => $context['key'],
                'published'     => 1,
                'deleted'       => 0,
                'hidemenu'      => 0,
                'richtext'      => 0,
                'cacheable'     => 0,
                'template'      => 0,
                'menuindex'     => $key
            ], $resource, [
                'content_type'  => $contentType ? $contentType->get('id') : 1
            ]), '', true, true);
            $object->save();
        }

        $settings[$resource['setting']] = $object->get('id');
    }

    foreach ($settings as $key => $value) {
        $setting = $modx->getObject(modSystemSetting::class, ['key' => 'digitalsignage.' . $key]);

        if ($setting && $setting->get('value') === '') {
            $setting->set('value', $value);
            $setting->save();
        }
    }

    foreach ($slideTypes as $slideType) {
        if (!$modx->getObject('DigitalSignageSlidesTypes', ['key' => $slideType['key']])) {
            $object = $modx->newObject('DigitalSignageSlidesTypes');
            $object->fromArray($slideType);
            $object->save();
        }
    }

    return true;
};
