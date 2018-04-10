<?php

    $xpdo_meta_map['DigitalSignageBroadcasts'] = [
        'package'       => 'digitalsignage',
        'version'       => '1.0',
        'table'         => 'digitalsignage_broadcasts',
        'extends'       => 'xPDOSimpleObject',
        'fields'        => [
            'id'            => null,
            'resource_id'   => null,
            'ticker_url'    => null,
            'color'         => null,
            'hash'          => null,
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
            'resource_id'   => [
                'dbtype'        => 'int',
                'precision'     => '11',
                'phptype'       => 'integer',
                'default'       => 0
            ],
            'ticker_url'    => [
                'dbtype'        => 'varchar',
                'precision'     => '255',
                'phptype'       => 'string',
                'default'       => ''
            ],
            'color'         => [
                'dbtype'        => 'int',
                'precision'     => '1',
                'phptype'       => 'integer',
                'default'       => 0
            ],
            'hash'          => [
                'dbtype'        => 'varchar',
                'precision'     => '255',
                'phptype'       => 'string',
                'default'       => ''
            ],
            'editedon'      => [
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
            'modResource'   => [
                'local'         => 'resource_id',
                'class'         => 'modResource',
                'foreign'       => 'id',
                'owner'         => 'foreign',
                'cardinality'   => 'one'
            ],
            'getSlides'     => [
                'local'         => 'id',
                'class'         => 'DigitalSignageBroadcastsSlides',
                'foreign'       => 'broadcast_id',
                'owner'         => 'local',
                'cardinality'   => 'many'
            ],
            'getFeeds'      => [
                'local'         => 'id',
                'class'         => 'DigitalSignageBroadcastsFeeds',
                'foreign'       => 'broadcast_id',
                'owner'         => 'local',
                'cardinality'   => 'many'
            ],
            'getSchedules'  => [
                'local'         => 'id',
                'class'         => 'DigitalSignagePlayersSchedules',
                'foreign'       => 'broadcast_id',
                'owner'         => 'local',
                'cardinality'   => 'many'
            ]
        ]
    ];

?>