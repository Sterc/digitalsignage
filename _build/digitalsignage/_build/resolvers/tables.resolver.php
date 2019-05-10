<?php

/**
 * Digital Signage
 *
 * Copyright 2019 by Sterc <modx@sterc.nl>
 */

set_time_limit(0);

if (!function_exists('updateTableColumns')) {
    /**
     * @param modX $modx
     * @param string $table
     */
    function updateTableColumns($modx, $table)
    {
        $tableName = $modx->getTableName($table);
        $tableName = str_replace('`', '', $tableName);
        $dbname = $modx->getOption('dbname');

        $c = $modx->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = :dbName AND table_name = :tableName");
        $c->bindParam(':dbName', $dbname);
        $c->bindParam(':tableName', $tableName);
        $c->execute();

        $unusedColumns = $c->fetchAll(PDO::FETCH_COLUMN, 0);
        $unusedColumns = array_flip($unusedColumns);

        $meta = $modx->getFieldMeta($table);
        $columns = array_keys($meta);

        $m = $modx->getManager();

        foreach ($columns as $column) {
            if (isset($unusedColumns[$column])) {
                $m->alterField($table, $column);
                $modx->log(modX::LOG_LEVEL_INFO, ' -- altered column: ' . $column);
                unset($unusedColumns[$column]);
            } else {
                $m->addField($table, $column);
                $modx->log(modX::LOG_LEVEL_INFO, ' -- added column: ' . $column);
            }
        }

        foreach ($unusedColumns as $column => $v) {
            $m->removeField($table, $column);
            $modx->log(modX::LOG_LEVEL_INFO, ' -- removed column: ' . $column);
        }
    }
}

if (!function_exists('updateTableIndexes')) {
    /**
     * @param modX $modx
     * @param string $table
     */
    function updateTableIndexes($modx, $table)
    {
        $tableName = $modx->getTableName($table);
        $tableName = str_replace('`', '', $tableName);
        $dbname = $modx->getOption('dbname');

        $c = $modx->prepare("SELECT DISTINCT INDEX_NAME FROM INFORMATION_SCHEMA.STATISTICS WHERE table_schema = :dbName AND table_name = :tableName AND INDEX_NAME != 'PRIMARY'");
        $c->bindParam(':dbName', $dbname);
        $c->bindParam(':tableName', $tableName);
        $c->execute();

        $oldIndexes = $c->fetchAll(PDO::FETCH_COLUMN, 0);

        $m = $modx->getManager();

        foreach ($oldIndexes as $oldIndex) {
            $m->removeIndex($table, $oldIndex);
            $modx->log(modX::LOG_LEVEL_INFO, ' -- removed index: ' . $oldIndex);
        }

        $meta = $modx->getIndexMeta($table);
        $indexes = array_keys($meta);

        foreach ($indexes as $index) {
            if ($index == 'PRIMARY') continue;
            $m->addIndex($table, $index);
            $modx->log(modX::LOG_LEVEL_INFO, ' -- added index: ' . $index);
        }
    }
}

if (!function_exists('alterTable')) {
    /**
     * @param modX $modx
     * @param string $table
     */
    function alterTable($modx, $table)
    {
        $modx->log(modX::LOG_LEVEL_INFO, ' - Updating columns');
        updateTableColumns($modx, $table);

        $modx->log(modX::LOG_LEVEL_INFO, ' - Updating indexes');
        updateTableIndexes($modx, $table);
    }
}

if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
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
        case xPDOTransport::ACTION_UPGRADE:
            $modx =& $object->xpdo;
            $modx->addPackage('digitalsignage', $modx->getOption('digitalsignage.core_path', null, $modx->getOption('core_path') . 'components/digitalsignage/') . 'model/');

            $manager = $modx->getManager();

            alterTable($modx, 'DigitalSignageBroadcasts');
            alterTable($modx, 'DigitalSignageBroadcastsFeeds');
            alterTable($modx, 'DigitalSignageBroadcastsSlides');
            alterTable($modx, 'DigitalSignagePlayers');
            alterTable($modx, 'DigitalSignagePlayersSchedules');
            alterTable($modx, 'DigitalSignageSlides');
            alterTable($modx, 'DigitalSignageSlidesTypes');

            break;
    }
}

return true;
