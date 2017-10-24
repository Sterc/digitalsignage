<?php

	$xpdo_meta_map['DigitalSignageSlides']= array(
		'package' 	=> 'digitalsignage',
		'version' 	=> '1.0',
		'table' 	=> 'digitalsignage_slides',
		'extends' 	=> 'xPDOSimpleObject',
		'fields' 	=> array(
			'id'			=> null,
			'type'			=> null,
			'name'			=> null,
			'time'			=> null,
			'data'			=> null,
			'published'		=> null,
			'editedon'		=> null
		),
		'fieldMeta'	=> array(
			'id' 		=> array(
				'dbtype' 	=> 'int',
				'precision' => '11',
				'phptype' 	=> 'integer',
				'null' 		=> false,
				'index' 	=> 'pk',
				'generated'	=> 'native'
			),
			'type' 		=> array(
				'dbtype' 	=> 'varchar',
				'precision' => '75',
				'phptype' 	=> 'string',
				'null' 		=> false
			),
			'name' 		=> array(
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
			'published'	=> array(
				'dbtype' 	=> 'int',
				'precision' => '1',
				'phptype' 	=> 'integer',
				'null' 		=> false,
				'default'	=> 1
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
					'id' 		=> array(
						'collation' => 'A',
						'null' 		=> false,
					)
				)
			)
		),
		'aggregates' => array(
			'getSlideType' => array(
				'local' 		=> 'type',
				'class' 		=> 'DigitalSignageSlidesTypes',
				'foreign' 		=> 'key',
				'owner' 		=> 'foreign',
				'cardinality' 	=> 'one'
			),
			'getBroadcasts' => array(
				'local' 		=> 'id',
				'class' 		=> 'DigitalSignageBroadcastsSlides',
				'foreign'		=> 'slide_id',
				'owner' 		=> 'local',
				'cardinality' 	=> 'many'
			)
		)
	);

?>