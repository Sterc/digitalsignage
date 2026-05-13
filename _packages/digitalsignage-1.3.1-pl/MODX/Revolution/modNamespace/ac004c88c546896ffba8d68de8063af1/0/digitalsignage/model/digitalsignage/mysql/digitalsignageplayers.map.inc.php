<?php

/**
 * Digital Signage
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
 */

$xpdo_meta_map['DigitalSignagePlayers'] = [
    'package'       => 'digitalsignage',
    'version'       => '1.0',
    'table'         => 'digitalsignage_players',
    'extends'       => 'xPDOObject',
    'tableMeta'     => [
        'engine'        => 'InnoDB'
    ],
    'fields'        => [
        'id'            => null,
        'key'           => null,
        'name'          => null,
        'description'   => null,
        'type'          => null,
        'resolution'    => null,
        'restart'       => null,
        'last_online'   => null,
        'last_online_time'  => null,
        'last_broadcast_id' => null,
        'editedon'      => null
    ],
    'fieldMeta'     => [
        'id'            => [
            'dbtype'        => 'int',
            'precision'     => '11',
            'phptype'       => 'integer',
            'null'          => false,
            'index'         => 'pk',
            'generated'     => 'native'
        ],
        'key'           => [
            'dbtype'        => 'varchar',
            'precision'     => '75',
            'phptype'       => 'string',
            'default'       => ''
        ],
        'name'          => [
            'dbtype'        => 'varchar',
            'precision'     => '75',
            'phptype'       => 'string',
            'default'       => ''
        ],
        'description'   => [
            'dbtype'        => 'varchar',
            'precision'     => '255',
            'phptype'       => 'string',
            'default'       => ''
        ],
        'type'          => [
            'dbtype'        => 'varchar',
            'precision'     => '255',
            'phptype'       => 'string',
            'default'       => ''
        ],
        'resolution'    => [
            'dbtype'        => 'varchar',
            'precision'     => '15',
            'phptype'       => 'string',
            'default'       => ''
        ],
        'restart'       => [
            'dbtype'        => 'int',
            'precision'     => '1',
            'phptype'       => 'integer',
            'default'       => 0
        ],
        'last_online'   => [
            'dbtype'        => 'timestamp',
            'phptype'       => 'timestamp',
            'default'       => '0000-00-00 00:00:00'
        ],
        'last_online_time'  => [
            'dbtype'        => 'int',
            'precision'     => '5',
            'phptype'       => 'integer',
            'default'       => 900
        ],
        'last_broadcast_id' => [
            'dbtype'        => 'int',
            'precision'     => '11',
            'phptype'       => 'integer',
            'null'          => true,
            'default'       => 0
        ],
        'editedon'          => [
            'dbtype'        => 'timestamp',
            'phptype'       => 'timestamp',
            'attributes'    => 'ON UPDATE CURRENT_TIMESTAMP',
            'null'          => false
        ]
    ],
    'indexes'       => [
        'PRIMARY'       => [
            'alias'         => 'PRIMARY',
            'primary'       => true,
            'unique'        => true,
            'columns'       => [
                'id'            => [
                    'collation'     => 'A',
                    'null'          => false
                ]
            ]
        ]
    ],
    'aggregates'    => [
        'getSchedules'  => [
            'local'         => 'id',
            'class'         => 'DigitalSignagePlayersSchedules',
            'foreign'       => 'player_id',
            'owner'         => 'local',
            'cardinality'   => 'many'
        ],
        'getCurrentBroadcast' => [
            'local'         => 'last_broadcast_id',
            'class'         => 'DigitalSignageBroadcasts',
            'foreign'       => 'id',
            'owner'         => 'local',
            'cardinality'   => 'one'
        ]
    ]
];
