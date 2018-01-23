<?php

	$xpdo_meta_map['DigitalSignageBroadcasts']= array(
		'package' 	=> 'digitalsignage',
		'version' 	=> '1.0',
		'table' 	=> 'digitalsignage_broadcasts',
		'extends' 	=> 'xPDOSimpleObject',
		'fields' 	=> array(
			'id'			=> null,
			'resource_id'	=> null,
			'ticker_url'	=> null,
			'color'			=> null,
			'hash'			=> null,
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
			'resource_id' => array(
				'dbtype' 	=> 'int',
				'precision' => '11',
				'phptype' 	=> 'integer',
				'null' 		=> false
			),
			'ticker_url' => array(
				'dbtype' 	=> 'varchar',
				'precision' => '255',
				'phptype' 	=> 'string',
				'null' 		=> false
			),
			'color' 	=> array(
				'dbtype' 	=> 'int',
				'precision' => '1',
				'phptype' 	=> 'integer',
				'null' 		=> false
			),
			'hash' 	=> array(
				'dbtype' 	=> 'varchar',
				'precision' => '255',
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
					'id' 		=> array(
						'collation' => 'A',
						'null' 		=> false,
					)
				)
			)
		),
		'aggregates' => array(
			'modResource' => array(
				'local'			=> 'resource_id',
				'class' 		=> 'modResource',
				'foreign' 		=> 'id',
				'owner' 		=> 'foreign',
				'cardinality' 	=> 'one'
			),
			'getSlides' => array(
				'local' 		=> 'id',
				'class' 		=> 'DigitalSignageBroadcastsSlides',
				'foreign'		=> 'broadcast_id',
				'owner' 		=> 'local',
				'cardinality' 	=> 'many'
			),
			'getFeeds' => array(
				'local' 		=> 'id',
				'class' 		=> 'DigitalSignageBroadcastsFeeds',
				'foreign'		=> 'broadcast_id',
				'owner' 		=> 'local',
				'cardinality' 	=> 'many'
			),
			'getSchedules' => array(
				'local' 		=> 'id',
				'class' 		=> 'DigitalSignagePlayersSchedules',
				'foreign'		=> 'broadcast_id',
				'owner' 		=> 'local',
				'cardinality' 	=> 'many'
			)
		)
	);

?>