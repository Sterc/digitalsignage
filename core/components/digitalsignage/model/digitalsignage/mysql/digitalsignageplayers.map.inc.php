<?php

	/**
	 * DigitalSignage
	 *
	 * Copyright 2016 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
	 *
	 * DigitalSignage is free software; you can redistribute it and/or modify it under
	 * the terms of the GNU General Public License as published by the Free Software
	 * Foundation; either version 2 of the License, or (at your option) any later
	 * version.
	 *
	 * DigitalSignage is distributed in the hope that it will be useful, but WITHOUT ANY
	 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
	 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
	 *
	 * You should have received a copy of the GNU General Public License along with
	 * DigitalSignage; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
	 * Suite 330, Boston, MA 02111-1307 USA
	 */

	$xpdo_meta_map['DigitalSignagePlayers']= array(
		'package' 	=> 'digitalsignage',
		'version' 	=> '1.0',
		'table' 	=> 'digitalsignage_players',
		'extends' 	=> 'xPDOSimpleObject',
		'fields' 	=> array(
			'id'				=> null,
			'key' 				=> null,
			'name' 				=> null,
			'description'		=> null,
			'type'				=> null,
			'resolution'		=> null,
			'restart'           => null,
			'last_online'		=> null,
			'last_online_time'  => null,
			'last_broadcast_id' => null,
			'editedon' 			=> null
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
			'key' 		=> array(
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
			'description' => array(
				'dbtype' 	=> 'varchar',
				'precision' => '255',
				'phptype' 	=> 'string',
				'null' 		=> false
			),
			'type' 	=> array(
				'dbtype' 	=> 'varchar',
				'precision' => '255',
				'phptype' 	=> 'string',
				'null' 		=> false
			),
			'resolution' => array(
				'dbtype' 	=> 'varchar',
				'precision' => '15',
				'phptype' 	=> 'string',
				'null' 		=> false
			),
            'restart' 	=> array(
                'dbtype' 	=> 'int',
                'precision' => '1',
                'phptype' 	=> 'integer',
                'null' 		=> false,
                'default'   => 0
            ),
			'last_online' => array(
				'dbtype' 	=> 'timestamp',
				'phptype' 	=> 'timestamp',
				'null' 		=> false,
                'default'   => '0000-00-00 00:00:00'
			),
            'last_online_time' => array(
                'dbtype' 	=> 'int',
                'precision' => '5',
                'phptype' 	=> 'integer',
                'null' 		=> false,
                'default'   => 900
            ),
			'last_broadcast_id' => array(
				'dbtype' 	=> 'int',
				'precision' => '11',
				'phptype' 	=> 'integer',
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
			'getSchedules' => array(
				'local' 		=> 'id',
				'class' 		=> 'DigitalSignagePlayersSchedules',
				'foreign'		=> 'player_id',
				'owner' 		=> 'local',
				'cardinality' 	=> 'many'
			),
			'getCurrentBroadcast' => array(
				'local' 		=> 'last_broadcast_id',
				'class' 		=> 'DigitalSignageBroadcasts',
				'foreign'		=> 'id',
				'owner' 		=> 'local',
				'cardinality' 	=> 'one'
			)
		)
	);

?>