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

	class NarrowcastingBroadcastSchedulesUpdateProcessor extends modObjectUpdateProcessor {
		/**
		 * @access public.
		 * @var String.
		 */
		public $classKey = 'NarrowcastingBroadcastsSchedules';
		
		/**
		 * @access public.
		 * @var Array.
		 */
		public $languageTopics = array('narrowcasting:default');
		
		/**
		 * @access public.
		 * @var String.
		 */
		public $objectType = 'narrowcasting.schedules';
		
		/**
		 * @access public.
		 * @var Object.
		 */
		public $narrowcasting;
		
		/**
		 * @acces public.
		 * @return Mixed.
		 */
		public function initialize() {
			$this->narrowcasting = $this->modx->getService('narrowcasting', 'Narrowcasting', $this->modx->getOption('narrowcasting.core_path', null, $this->modx->getOption('core_path').'components/narrowcasting/').'model/narrowcasting/');

			if (null !== ($type = $this->getProperty('type'))) {
				if ('day' == $type) {
					$this->setProperty('start_date', '0000-00-00');
					$this->setProperty('end_date', '0000-00-00');
				} else if ('date' == $type) {
					$this->setProperty('end_date', $this->getEmptyDate($this->getProperty('end_date'), $this->getProperty('start_date')));
					
					$this->setProperty('day', 0);
				}
			}
			
			if (null !== $this->getProperty('entire_day')) {
				$this->setProperty('start_time', '00:00:00');
				$this->setProperty('end_time', '00:00:00');
			}
			
			return parent::initialize();
		}
		
		/**
		 * @access public.
		 * @return Mixed.
		 */
		public function beforeSave() {
			// Moet nog, zie beforeSave functie in create.class.php
			
			return parent::beforeSave();	
		}
	}
	
	return 'NarrowcastingBroadcastSchedulesUpdateProcessor';
	
?>