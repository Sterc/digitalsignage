<?php

/**
 * Digital Signage
 *
 * Copyright 2019 by Sterc <modx@sterc.nl>
 */

set_time_limit(0);

if (!function_exists('removeTablePrimaryKeyIndexes')) {
    /**
     * Removes all table primary key indexes when column name is not id.
     *
     * @param modX $modx
     * @param String $table.
     */
    function removeTablePrimaryKeyIndexes(modX $modx, $table)
    {
        $tableName = str_replace('`', '', $modx->getTableName($table));

        $criteria = $modx->prepare('SELECT DISTINCT INDEX_NAME, COlUMN_NAME FROM INFORMATION_SCHEMA.STATISTICS WHERE table_schema = "' . $modx->getOption('dbname') . '" AND table_name = "' . $tableName . '"');

        $criteria->execute();

        $oldIndexes = $criteria->fetchAll(PDO::FETCH_ASSOC);

        foreach ($oldIndexes as $key => $oldIndex) {
            if ($oldIndex['INDEX_NAME'] === 'PRIMARY') {
                if ($oldIndex['COlUMN_NAME'] !== 'id') {
                    if ($modx->getManager()->removeIndex($table, $oldIndex['INDEX_NAME'])) {
                        //$modx->log(modX::LOG_LEVEL_INFO, ' -- remove primary key index ' . $oldIndex['INDEX_NAME']);
                    }
                }
            }
        }
    }
}

if (!function_exists('updateTableColumns')) {
    /**
     * Updates all table columns.
     *
     * @param modX $modx.
     * @param String $table.
     */
    function updateTableColumns(modX $modx, $table)
    {
        $tableName = str_replace('`', '', $modx->getTableName($table));

        $criteria = $modx->prepare('SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = "' . $modx->getOption('dbname') . '" AND table_name = "' . $tableName . '"');

        $criteria->execute();

        $newColumns = array_keys($modx->getFieldMeta($table));
        $oldColumns = $criteria->fetchAll(PDO::FETCH_COLUMN, 0);

        if (in_array('id', $newColumns, true) && !in_array('id', $oldColumns, true)) {
            $modx->exec('ALTER TABLE `' . $tableName . '` ADD COLUMN `id` INT(11) NOT NULL AUTO_INCREMENT primary key');

            array_unshift($oldColumns,'id');
        }

        foreach ($newColumns as $key => $newColumn) {
            $options = [];

            if ($key === 0) {
                $option['first'] = true;
            } else {
                $options['after'] = $newColumns[$key - 1];
            }

            if (!in_array($newColumn, $oldColumns, true)) {
                if ($modx->getManager()->addField($table, $newColumn, $options)) {
                    //$modx->log(modX::LOG_LEVEL_INFO, ' -- add column ' . $newColumn);
                }
            } else {
                if ($modx->getManager()->alterField($table, $newColumn, $options)) {
                    //$modx->log(modX::LOG_LEVEL_INFO, ' -- alter column ' . $newColumn);
                }

                unset($oldColumns[array_search($newColumn, $oldColumns, true)]);
            }
        }

        foreach (array_keys(array_flip($oldColumns)) as $oldColumn) {
            if ($modx->getManager()->removeField($table, $oldColumn)) {
                //$modx->log(modX::LOG_LEVEL_INFO, ' -- removed column: ' . $oldColumn);
            }
        }
    }
}

if (!function_exists('updateTableIndexes')) {
    /**
     * Updates all table columns.
     *
     * @param modX $modx
     * @param String $table
     */
    function updateTableIndexes(modX $modx, $table)
    {
        $tableName = str_replace('`', '', $modx->getTableName($table));

        $criteria = $modx->prepare('SELECT DISTINCT INDEX_NAME FROM INFORMATION_SCHEMA.STATISTICS WHERE table_schema = "' . $modx->getOption('dbname') . '" AND table_name = "' . $tableName . '"');

        $criteria->execute();

        $oldIndexes = $criteria->fetchAll(PDO::FETCH_COLUMN, 0);

        foreach ($oldIndexes as $oldIndex) {
            if ($oldIndex !== 'PRIMARY') {
                if ($modx->getManager()->removeIndex($table, $oldIndex)) {
                    //$modx->log(modX::LOG_LEVEL_INFO, ' -- remove index ' . $oldIndex);
                }
            }
        }

        foreach (array_keys($modx->getIndexMeta($table)) as $newIndex) {
            if ($newIndex === 'PRIMARY' && count($oldIndexes) >= 1) {
                continue;
            }

            if ($modx->getManager()->addIndex($table, $newIndex)) {
                //$modx->log(modX::LOG_LEVEL_INFO, ' -- add index ' . $newIndex);
            }
        }
    }
}

if (!function_exists('updateObjectContainer')) {
    /**
     * Updates a table with new columns and indexes.
     *
     * @param modX $modx.
     * @param String $table.
     */
    function updateObjectContainer(modX $modx, $table)
    {
        //$modx->log(modX::LOG_LEVEL_INFO, ' - Update primary key indexes');
        removeTablePrimaryKeyIndexes($modx, $table);

        //$modx->log(modX::LOG_LEVEL_INFO, ' - Updating columns');
        updateTableColumns($modx, $table);

        //$modx->log(modX::LOG_LEVEL_INFO, ' - Updating indexes');
        updateTableIndexes($modx, $table);
    }
}

if (!function_exists('migrateSlideTypes')) {
    /**
     * In 1.2.0 type column is changed to id column instead of key column. Migrate the old
     * data to the new database version.
     *
     * @param modX $modx.
     * @param String $table.
     * @param String $type.
     * @param Array $migrate.
     */
    function migrateSlideTypes(modX $modx, $table, $type, &$migrate)
    {
        if ($type === 'before') {
            $criteria = $modx->prepare('SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = "' . $modx->getOption('dbname') . '" AND table_name = "' . str_replace('`', '', $modx->getTableName($table)) . '" AND COLUMN_NAME = "type"');

            $criteria->execute();

            $current = $criteria->fetchAll(PDO::FETCH_COLUMN);

            if (isset($current[0]) && strpos($current[0], 'varchar') !== false) {
                $criteria = $modx->prepare('SELECT id, type FROM `' . str_replace('`', '', $modx->getTableName($table)) . '`');

                $criteria->execute();

                foreach ($criteria->fetchAll(PDO::FETCH_ASSOC) as $slide) {
                    $migrate[(int) $slide['id']] = $slide['type'];
                }
            }
        } else if ($type === 'after') {
            if (is_array($migrate) && count($migrate) >= 1) {
                foreach ($modx->getCollection($table) as $slide) {
                    if (isset($migrate[(int) $slide->get('id')])) {
                        $type = $modx->getObject('DigitalSignageSlidesTypes', [
                            'key' => $migrate[(int) $slide->get('id')]
                        ]);

                        if ($type) {
                            $slide->set('type', (int) $type->get('id'));
                            $slide->save();
                        }
                    }
                }
            }
        }
    }
}

if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
            $modx =& $object->xpdo;
            $modx->addPackage('digitalsignage', $modx->getOption('digitalsignage.core_path', null, $modx->getOption('core_path') . 'components/digitalsignage/') . 'model/');

            $modx->getManager()->createObjectContainer('DigitalSignageBroadcasts');
            $modx->getManager()->createObjectContainer('DigitalSignageBroadcastsFeeds');
            $modx->getManager()->createObjectContainer('DigitalSignageBroadcastsSlides');
            $modx->getManager()->createObjectContainer('DigitalSignagePlayers');
            $modx->getManager()->createObjectContainer('DigitalSignagePlayersSchedules');
            $modx->getManager()->createObjectContainer('DigitalSignageSlides');
            $modx->getManager()->createObjectContainer('DigitalSignageSlidesTypes');

            break;
        case xPDOTransport::ACTION_UPGRADE:
            $modx =& $object->xpdo;
            $modx->addPackage('digitalsignage', $modx->getOption('digitalsignage.core_path', null, $modx->getOption('core_path') . 'components/digitalsignage/') . 'model/');

            migrateSlideTypes($modx, 'DigitalSignageSlides', 'before', $migrate);

            updateObjectContainer($modx, 'DigitalSignageBroadcasts');
            updateObjectContainer($modx, 'DigitalSignageBroadcastsFeeds');
            updateObjectContainer($modx, 'DigitalSignageBroadcastsSlides');
            updateObjectContainer($modx, 'DigitalSignagePlayers');
            updateObjectContainer($modx, 'DigitalSignagePlayersSchedules');
            updateObjectContainer($modx, 'DigitalSignageSlides');
            updateObjectContainer($modx, 'DigitalSignageSlidesTypes');

            migrateSlideTypes($modx, 'DigitalSignageSlides', 'after', $migrate);

            break;
    }
}

return true;
