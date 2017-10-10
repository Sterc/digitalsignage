<?php

	/**
	 * Narrowcasting
	 *
	 * Copyright 2016 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
	 *
	 * Narrowcasting is free software; you can redistribute it and/or modify it under
	 * the terms of the GNU General Public License as published by the Free Software
	 * Foundation; either version 2 of the License, or (at your option) any later
	 * version.
	 *
	 * Narrowcasting is distributed in the hope that it will be useful, but WITHOUT ANY
	 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
	 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
	 *
	 * You should have received a copy of the GNU General Public License along with
	 * Narrowcasting; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
	 * Suite 330, Boston, MA 02111-1307 USA
	 */

	$xpdo_meta_map['NarrowcastingPlayersSchedules']= array(
		'package' 	=> 'narrowcasting',
		'version' 	=> '1.0',
		'table' 	=> 'narrowcasting_players_schedules',
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
				'class' 		=> 'NarrowcastingPlayers',
				'foreign' 		=> 'id',
				'owner' 		=> 'foreign',
				'cardinality' 	=> 'one'
			),
			'getBroadcast' => array(
				'local' 		=> 'broadcast_id',
				'class' 		=> 'NarrowcastingBroadcasts',
				'foreign' 		=> 'id',
				'owner' 		=> 'foreign',
				'cardinality' 	=> 'one'
			)
		)
	);

?>