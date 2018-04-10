<?php

    $xpdo_meta_map['DigitalSignageSlides'] = [
        'package'       => 'digitalsignage',
        'version'       => '1.0',
        'table'         => 'digitalsignage_slides',
        'extends'       => 'xPDOSimpleObject',
        'fields'        => [
            'id'            => null,
            'type'          => null,
            'name'          => null,
            'time'          => null,
            'data'          => null,
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
            'type'          => [
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
            'time'          => [
                'dbtype'        => 'int',
                'precision'     => '3',
                'phptype'       => 'integer',
                'default'       => 10
            ],
            'data'          => [
                'dbtype'        => 'text',
                'phptype'       => 'string',
                'default'       => ''
            ],
            'published'     => [
                'dbtype'        => 'int',
                'precision'     => '1',
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
            'getSlideType'  => [
                'local'         => 'type',
                'class'         => 'DigitalSignageSlidesTypes',
                'foreign'       => 'key',
                'owner'         => 'foreign',
                'cardinality'   => 'one'
            ],
            'getBroadcasts' => [
                'local'         => 'id',
                'class'         => 'DigitalSignageBroadcastsSlides',
                'foreign'       => 'slide_id',
                'owner'         => 'local',
                'cardinality'   => 'many'
            ]
        ]
    ];

?>