<?php

	$xpdo_meta_map['DigitalSignageBroadcastsSlides']= array(
		'package' 	=> 'digitalsignage',
		'version' 	=> '1.0',
		'table' 	=> 'digitalsignage_broadcasts_slides',
		'extends' 	=> 'xPDOSimpleObject',
		'fields' 	=> array(
			'id'				=> null,
			'broadcast_id'		=> null,
			'slide_id'			=> null,
			'sortindex'			=> null
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
			'broadcast_id' => array(
				'dbtype' 	=> 'int',
				'precision' => '11',
				'phptype' 	=> 'integer',
				'null' 		=> false
			),
			'slide_id' => array(
				'dbtype' 	=> 'int',
				'precision' => '11',
				'phptype' 	=> 'integer',
				'null' 		=> false
			),
			'sortindex' => array(
				'dbtype' 	=> 'int',
				'precision' => '11',
				'phptype' 	=> 'integer',
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
			'getSlide' => array(
				'local' 		=> 'slide_id',
				'class' 		=> 'DigitalSignageSlides',
				'foreign' 		=> 'id',
				'owner' 		=> 'foreign',
				'cardinality' 	=> 'one'
			),
			'getBroadcast' => array(
				'local' 		=> 'broadcast_id',
				'class' 		=> 'DigitalSignageBroadcasts',
				'foreign' 		=> 'id',
				'owner' 		=> 'foreign',
				'cardinality' 	=> 'one'
			)
		)
	);

?>