<?php

	if ($object->xpdo) {
	    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
	        case xPDOTransport::ACTION_INSTALL:
	            $modx =& $object->xpdo;
	            $modx->addPackage('narrowcasting', $modx->getOption('narrowcasting.core_path', null, $modx->getOption('core_path').'components/narrowcasting/').'model/');

	            $manager = $modx->getManager();
	
	            $manager->createObjectContainer('NarrowcastingBroadcasts');
                $manager->createObjectContainer('NarrowcastingBroadcastsFeeds');
                $manager->createObjectContainer('NarrowcastingBroadcastsSlides');
                $manager->createObjectContainer('NarrowcastingPlayers');
                $manager->createObjectContainer('NarrowcastingPlayersSchedules');
                $manager->createObjectContainer('NarrowcastingSlides');
                $manager->createObjectContainer('NarrowcastingSlidesTypes');

	            break;
	        case xPDOTransport::ACTION_UPGRADE:
	            break;
	    }
	}
	
	return true;