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

	$xpdo_meta_map['NarrowcastingSlidesTypes']= array(
		'package' 	=> 'narrowcasting',
		'version' 	=> '1.0',
		'table' 	=> 'narrowcasting_slides_types',
		'extends' 	=> 'xPDOObject',
		'fields' 	=> array(
			'key'			=> null,
			'name'			=> null,
			'description'	=> null,
			'icon'			=> null,
			'data'			=> null
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
			'data' 		=> array(
				'dbtype' 	=> 'text',
				'phptype' 	=> 'string',
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
			'NarrowcastingSlides' => array(
				'local' 		=> 'key',
				'class' 		=> 'NarrowcastingSlides',
				'foreign'		=> 'type',
				'owner' 		=> 'local',
				'cardinality' 	=> 'many'
			)
		)
	);

?>