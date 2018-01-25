<?php

$package = 'DigitalSignageSocialMedia';

$success = false;

/**
 * The needed resources.
 */
$resources = [
    [
        'pagetitle'     => 'Export (social media)',
        'content'       => '[[!DigitalSignageSocialMedia? &toJson=`1`]]',
        'content_type'  => 7,
        'uri'           => 'social-media-export.json',
        'uri_override'  => 1,
    ]
];

switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
    case xPDOTransport::ACTION_UPGRADE:
        /**
         * Install the needed resources.
         */
        foreach ($resources as $key => $resource) {
            $resource = array_merge([
                'context_key'   => 'ds',
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
                    $object->xpdo->log(
                        xPDO::LOG_LEVEL_ERROR, $resource['pagetitle'] . ' resource could not be created.'
                    );
                }
            } else {
                if (isset($resource['setting'])) {
                    if (isset($settings[$resource['setting']])) {
                        $settings[$resource['setting']]['value'] = $resourceObject->get('id');
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