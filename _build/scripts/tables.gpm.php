<?php

use xPDO\Transport\xPDOTransport;

return function ($modx, $action) {
    if ($action === xPDOTransport::ACTION_UNINSTALL) {
        return true;
    }

    set_time_limit(0);

    $modx->addPackage('digitalsignage', $modx->getOption('digitalsignage.core_path', null, $modx->getOption('core_path') . 'components/digitalsignage/') . 'model/');

    $manager = $modx->getManager();
    $database = $modx->getOption('dbname');

    $tables = [
        'DigitalSignageBroadcasts',
        'DigitalSignageBroadcastsFeeds',
        'DigitalSignageBroadcastsSlides',
        'DigitalSignagePlayers',
        'DigitalSignagePlayersSchedules',
        'DigitalSignageSlides',
        'DigitalSignageSlidesTypes',
    ];

    foreach ($tables as $table) {
        $tableName = str_replace('`', '', $modx->getTableName($table));

        $query = $modx->prepare('SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLES WHERE table_schema = ? AND table_name = ?');
        $query->execute([$database, $tableName]);

        if (!(int) $query->fetchColumn()) {
            $manager->createObjectContainer($table);

            continue;
        }

        $query = $modx->prepare('SELECT DISTINCT INDEX_NAME, COLUMN_NAME FROM INFORMATION_SCHEMA.STATISTICS WHERE table_schema = ? AND table_name = ?');
        $query->execute([$database, $tableName]);

        foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $index) {
            if ($index['INDEX_NAME'] === 'PRIMARY' && $index['COLUMN_NAME'] !== 'id') {
                $manager->removeIndex($table, $index['INDEX_NAME']);
            }
        }

        $query = $modx->prepare('SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = ? AND table_name = ?');
        $query->execute([$database, $tableName]);

        $newColumns = array_keys($modx->getFieldMeta($table));
        $oldColumns = $query->fetchAll(PDO::FETCH_COLUMN, 0);

        if (in_array('id', $newColumns, true) && !in_array('id', $oldColumns, true)) {
            $modx->exec('ALTER TABLE `' . $tableName . '` ADD COLUMN `id` INT(11) NOT NULL AUTO_INCREMENT primary key');

            array_unshift($oldColumns, 'id');
        }

        foreach ($newColumns as $key => $newColumn) {
            $options = $key === 0 ? ['first' => true] : ['after' => $newColumns[$key - 1]];

            if (!in_array($newColumn, $oldColumns, true)) {
                $manager->addField($table, $newColumn, $options);
            } else {
                $manager->alterField($table, $newColumn, $options);

                unset($oldColumns[array_search($newColumn, $oldColumns, true)]);
            }
        }

        foreach (array_unique($oldColumns) as $oldColumn) {
            $manager->removeField($table, $oldColumn);
        }

        $query = $modx->prepare('SELECT DISTINCT INDEX_NAME FROM INFORMATION_SCHEMA.STATISTICS WHERE table_schema = ? AND table_name = ?');
        $query->execute([$database, $tableName]);

        $oldIndexes = $query->fetchAll(PDO::FETCH_COLUMN, 0);

        foreach ($oldIndexes as $oldIndex) {
            if ($oldIndex !== 'PRIMARY') {
                $manager->removeIndex($table, $oldIndex);
            }
        }

        foreach (array_keys($modx->getIndexMeta($table)) as $newIndex) {
            if ($newIndex === 'PRIMARY' && count($oldIndexes) >= 1) {
                continue;
            }

            $manager->addIndex($table, $newIndex);
        }
    }

    return true;
};
