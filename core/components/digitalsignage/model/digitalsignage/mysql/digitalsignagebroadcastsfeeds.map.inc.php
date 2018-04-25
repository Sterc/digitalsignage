<?php

    $xpdo_meta_map['DigitalSignageBroadcastsFeeds'] = [
        'package'       => 'digitalsignage',
        'version'       => '1.0',
        'table'         => 'digitalsignage_broadcasts_feeds',
        'extends'       => 'xPDOObject',
        'tableMeta'     => [
            'engine'        => 'InnoDB'
        ],
        'fields'        => [
            'id'            => null,
            'broadcast_id'  => null,
            'key'           => null,
            'url'           => null,
            'time'          => null,
            'frequency'     => null,
            'published'     => null,
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
            'broadcast_id'  => [
                'dbtype'        => 'int',
                'precision'     => '11',
                'phptype'       => 'integer',
                'default'       => 0
            ],
            'key'           => [
                'dbtype'        => 'varchar',
                'precision'     => '75',
                'phptype'       => 'string',
                'default'       => ''
            ],
            'url'           => [
                'dbtype'        => 'varchar',
                'precision'     => '255',
                'phptype'       => 'string',
                'default'       => ''
            ],
            'time'          => [
                'dbtype'        => 'int',
                'precision'     => '3',
                'phptype'       => 'integer',
                'default'       => 15
            ],
            'frequency'     => [
                'dbtype'        => 'int',
                'precision'     => '3',
                'phptype'       => 'integer',
                'default'       => 2
            ],
            'published'     => [
                'dbtype'        => 'int',
                'precision'     => '3',
                'phptype'       => 'integer',
                'default'       => 1
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
            'getBroadcast'  => [
                'local'         => 'broadcast_id',
                'class'         => 'DigitalSignageBroadcasts',
                'foreign'       => 'id',
                'owner'         => 'foreign',
                'cardinality'   => 'one'
            ]
        ]
    ];

?>