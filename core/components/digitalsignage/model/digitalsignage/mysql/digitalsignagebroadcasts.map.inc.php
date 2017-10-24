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