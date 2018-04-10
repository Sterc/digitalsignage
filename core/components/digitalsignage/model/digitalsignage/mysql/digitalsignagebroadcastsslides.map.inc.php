<?php

    $xpdo_meta_map['DigitalSignageBroadcastsSlides'] = [
        'package'       => 'digitalsignage',
        'version'       => '1.0',
        'table'         => 'digitalsignage_broadcasts_slides',
        'extends'       => 'xPDOSimpleObject',
        'fields'        => [
            'id'            => null,
            'broadcast_id'  => null,
            'slide_id'      => null,
            'sortindex'     => null
        ],
        'fieldMeta'     => [
            'id'            => [
                'dbtype'        => 'int',
                'precision'     => '11',
                'phptype'       => 'integer',
                'index'         => 'pk',
                'generated'     => 'native'
            ],
            'broadcast_id'  => [
                'dbtype'        => 'int',
                'precision'     => '11',
                'phptype'       => 'integer',
                'default'       => 0
            ],
            'slide_id'      => [
                'dbtype'        => 'int',
                'precision'     => '11',
                'phptype'       => 'integer',
                'default'       => 0
            ],
            'sortindex'     => [
                'dbtype'        => 'int',
                'precision'     => '11',
                'phptype'       => 'integer',
                'default'       => 0
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
            'getSlide'      => [
                'local'         => 'slide_id',
                'class'         => 'DigitalSignageSlides',
                'foreign'       => 'id',
                'owner'         => 'foreign',
                'cardinality'   => 'one'
            ],
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