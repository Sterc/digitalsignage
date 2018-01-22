<?php

	$xpdo_meta_map['DigitalSignageBroadcastsFeeds']= array(
		'package' 	=> 'digitalsignage',
		'version' 	=> '1.0',
		'table' 	=> 'digitalsignage_broadcasts_feeds',
		'extends' 	=> 'xPDOSimpleObject',
		'fields' 	=> array(
			'id'				=> null,
			'broadcast_id'		=> null,
			'key'				=> null,
			'url'				=> null,
			'time'				=> null,
			'frequency'			=> null,
			'published'			=> null,
			'editedon'			=> null
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
			'key' 		=> array(
				'dbtype' 	=> 'varchar',
				'precision' => '75',
				'phptype' 	=> 'string',
				'null' 		=> false
			),
			'url' 		=> array(
				'dbtype' 	=> 'varchar',
				'precision' => '255',
				'phptype' 	=> 'string',
				'null' 		=> false
			),
			'time' 		=> array(
				'dbtype' 	=> 'int',
				'precision' => '3',
				'phptype' 	=> 'integer',
				'null' 		=> false
			),
			'frequency' => array(
				'dbtype' 	=> 'int',
				'precision' => '3',
				'phptype' 	=> 'integer',
				'null' 		=> false
			),
			'published' => array(
				'dbtype' 	=> 'int',
				'precision' => '3',
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