<?php

/**
 * Digital Signage
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
 */

$xpdo_meta_map['DigitalSignageSlidesTypes'] = [
    'package'       => 'digitalsignage',
    'version'       => '1.0',
    'table'         => 'digitalsignage_slides_types',
    'extends'       => 'xPDOObject',
    'tableMeta'     => [
        'engine'        => 'InnoDB'
    ],
    'fields'        => [
        'id'            => null,
        'key'           => null,
        'name'          => null,
        'description'   => null,
        'icon'          => null,
        'time'          => null,
        'data'          => null,
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
            'null'          => false
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
        'icon'          => [
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
            'null'          => true,
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
        'getSlides'     => [
            'local'         => 'id',
            'class'         => 'DigitalSignageSlides',
            'foreign'       => 'type',
            'owner'         => 'local',
            'cardinality'   => 'many'
        ]
    ]
];
