<?php

	$xpdo_meta_map['DigitalSignagePlayersSchedules']= array(
		'package' 	=> 'digitalsignage',
		'version' 	=> '1.0',
		'table' 	=> 'digitalsignage_players_schedules',
		'extends' 	=> 'xPDOSimpleObject',
		'fields' 	=> array(
			'id'				=> null,
			'player_id'			=> null,
			'broadcast_id'		=> null,
			'description'		=> null,
			'type'				=> null,
			'start_time'		=> null,
			'start_date'		=> null,
			'end_time'			=> null,
			'end_date'			=> null,
			'day'				=> null
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
			'player_id' => array(
				'dbtype' 	=> 'int',
				'precision' => '11',
				'phptype' 	=> 'integer',
				'null' 		=> false
			),
			'broadcast_id' => array(
				'dbtype' 	=> 'int',
				'precision' => '11',
				'phptype' 	=> 'integer',
				'null' 		=> false
			),
			'description' => array(
				'dbtype' 	=> 'varchar',
				'precision' => '255',
				'phptype' 	=> 'string',
				'null' 		=> false
			),
			'type' 	=> array(
				'dbtype' 	=> 'varchar',
				'precision' => '5',
				'phptype' 	=> 'string',
				'null' 		=> false
			),
			'start_time' => array(
				'dbtype' 	=> 'time',
				'phptype' 	=> 'string',
				'null' 		=> false,
				'default'	=> '00:00:00'
			),
			'start_date' => array(
				'dbtype' 	=> 'date',
				'phptype' 	=> 'string',
				'null' 		=> false,
				'default'	=> '0000-00-00'
			),
			'end_time' 	=> array(
				'dbtype' 	=> 'time',
				'phptype' 	=> 'string',
				'null' 		=> false,
				'default'	=> '00:00:00'
			),
			'end_date'	=> array(
				'dbtype' 	=> 'date',
				'phptype' 	=> 'string',
				'null' 		=> false,
				'default'	=> '0000-00-00'
			),
			'day' 	=> array(
				'dbtype' 	=> 'int',
				'precision' => '1',
				'phptype' 	=> 'integer',
				'null' 		=> false
			),
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
			'getPlayer' => array(
				'local' 		=> 'player_id',
				'class' 		=> 'DigitalSignagePlayers',
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