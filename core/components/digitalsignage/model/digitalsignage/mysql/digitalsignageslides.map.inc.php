<?php

/**
 * Digital Signage
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
 */

$xpdo_meta_map['DigitalSignageSlides'] = [
    'package'       => 'digitalsignage',
    'version'       => '1.0',
    'table'         => 'digitalsignage_slides',
    'extends'       => 'xPDOObject',
    'tableMeta'     => [
        'engine'        => 'InnoDB'
    ],
    'fields'        => [
        'id'            => null,
        'type'          => null,
        'name'          => null,
        'time'          => null,
        'protected'     => null,
        'data'          => null,
        'published'     => null,
        'editedon'      => null,
        'pub_date'      => null,
        'unpub_date'    => null,
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
            'dbtype'        => 'int',
            'precision'     => '11',
            'phptype'       => 'integer',
            'null'          => false
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
        'protected'     => [
            'dbtype'        => 'int',
            'precision'     => '1',
            'phptype'       => 'integer',
            'default'       => 0
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
        ],
        'pub_date'      => [
            'dbtype'        => 'int',
            'precision'     => '20',
            'phptype'       => 'timestamp',
        ],
        'unpub_date'    => [
            'dbtype'        => 'int',
            'precision'     => '20',
            'phptype'       => 'timestamp',
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
        'getSlideType'  => [
            'local'         => 'type',
            'class'         => 'DigitalSignageSlidesTypes',
            'foreign'       => 'id',
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
