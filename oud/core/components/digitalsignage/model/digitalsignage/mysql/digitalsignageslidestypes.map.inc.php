<?php

	$xpdo_meta_map['DigitalSignageSlidesTypes']= array(
		'package' 	=> 'digitalsignage',
		'version' 	=> '1.0',
		'table' 	=> 'digitalsignage_slides_types',
		'extends' 	=> 'xPDOObject',
		'fields' 	=> array(
			'key'			=> null,
			'name'			=> null,
			'description'	=> null,
			'icon'			=> null,
			'time'			=> null,
			'data'			=> null,
			'editedon'		=> null
		),
		'fieldMeta'	=> array(
			'key' 		=> array(
				'dbtype' 	=> 'varchar',
				'precision' => '75',
				'phptype' 	=> 'string',
				'null' 		=> false,
				'index' 	=> 'pk'
			),
			'name' 		=> array(
				'dbtype' 	=> 'varchar',
				'precision' => '75',
				'phptype' 	=> 'string',
				'null' 		=> false
			),
			'description' => array(
				'dbtype' 	=> 'varchar',
				'precision' => '255',
				'phptype' 	=> 'string',
				'null' 		=> false
			),
			'icon' 		=> array(
				'dbtype' 	=> 'varchar',
				'precision' => '75',
				'phptype' 	=> 'string',
				'null' 		=> false
			),
			'time' 		=> array(
				'dbtype' 	=> 'int',
				'precision' => '3',
				'phptype' 	=> 'integer',
				'null' 		=> false
			),
			'data' 		=> array(
				'dbtype' 	=> 'text',
				'phptype' 	=> 'string',
				'null' 		=> false
			),
			'editedon' 	=> array(
				'dbtype' 	=> 'timestamp',
				'phptype' 	=> 'timestamp',
				'attributes' => 'ON UPDATE CURRENT_TIMESTAMP',
				'null' 		=> false
			)
		),
		'indexes'	=> array(
			'PRIMARY'	=> array(
				'alias' 	=> 'PRIMARY',
				'primary' 	=> true,
				'unique' 	=> true,
				'columns' 	=> array(
					'key' 		=> array(
						'collation' => 'A',
						'null' 		=> false,
					)
				)
			)
		),
		'aggregates' => array(
			'getSlides' => array(
				'local' 		=> 'key',
				'class' 		=> 'DigitalSignageSlides',
				'foreign'		=> 'type',
				'owner' 		=> 'local',
				'cardinality' 	=> 'many'
			)
		)
	);

?>