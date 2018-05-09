<?php

$action = $options[xPDOTransport::PACKAGE_ACTION];

if ($object->xpdo) {
    switch ($action) {
        case xPDOTransport::ACTION_INSTALL:
            $modx =& $object->xpdo;
            $modx->addPackage('digitalsignage', $modx->getOption('digitalsignage.core_path', null, $modx->getOption('core_path') . 'components/digitalsignage/') . 'model/');

            $manager = $modx->getManager();

            $manager->createObjectContainer('DigitalSignageBroadcasts');
            $manager->createObjectContainer('DigitalSignageBroadcastsFeeds');
            $manager->createObjectContainer('DigitalSignageBroadcastsSlides');
            $manager->createObjectContainer('DigitalSignagePlayers');
            $manager->createObjectContainer('DigitalSignagePlayersSchedules');
            $manager->createObjectContainer('DigitalSignageSlides');
            $manager->createObjectContainer('DigitalSignageSlidesTypes');

            break;
    }
}

return true;

?>