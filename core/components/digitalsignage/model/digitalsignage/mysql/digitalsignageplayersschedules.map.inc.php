<?php

    $xpdo_meta_map['DigitalSignagePlayersSchedules'] = [
        'package'       => 'digitalsignage',
        'version'       => '1.0',
        'table'         => 'digitalsignage_players_schedules',
        'extends'       => 'xPDOSimpleObject',
        'fields'        => [
            'id'            => null,
            'player_id'     => null,
            'broadcast_id'  => null,
            'description'   => null,
            'type'          => null,
            'start_time'    => null,
            'start_date'    => null,
            'end_time'      => null,
            'end_date'      => null,
            'day'           => null
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
            'player_id'     => [
                'dbtype'        => 'int',
                'precision'     => '11',
                'phptype'       => 'integer',
                'default'       => 0
            ],
            'broadcast_id'  => [
                'dbtype'        => 'int',
                'precision'     => '11',
                'phptype'       => 'integer',
                'default'       => 0
            ],
            'description'   => [
                'dbtype'        => 'varchar',
                'precision'     => '255',
                'phptype'       => 'string',
                'default'       => ''
            ],
            'type'          => [
                'dbtype'        => 'varchar',
                'precision'     => '5',
                'phptype'       => 'string',
                'default'       => ''
            ],
            'start_time'    => [
                'dbtype'        => 'time',
                'phptype'       => 'string',
                'default'       => '00:00:00'
            ],
            'start_date'    => [
                'dbtype'        => 'date',
                'phptype'       => 'string',
                'default'       => '0000-00-00'
            ],
            'end_time'      => [
                'dbtype'        => 'time',
                'phptype'       => 'string',
                'default'       => '00:00:00'
            ],
            'end_date'      => [
                'dbtype'        => 'date',
                'phptype'       => 'string',
                'default'       => '0000-00-00'
            ],
            'day'           => [
                'dbtype'        => 'int',
                'precision'     => '1',
                'phptype'       => 'integer',
                'default'       => 0
            ],
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
            'getPlayer'     => [
                'local'         => 'player_id',
                'class'         => 'DigitalSignagePlayers',
                'foreign'       => 'id',
                'owner'         => 'foreign',
                'cardinality'   => 'one'
            ],
            'getBroadcast' => [
                'local'         => 'broadcast_id',
                'class'         => 'DigitalSignageBroadcasts',
                'foreign'       => 'id',
                'owner'         => 'foreign',
                'cardinality'   => 'one'
            ]
        ]
    ];

?>