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
		public $classKey = 'NarrowcastingPlayersSchedules';
		
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

			return parent::initialize();
		}
		
		/**
		 * @access public.
		 * @return Mixed.
		 */
		public function beforeSave() {
			if ($this->object->is('day')) {
				$start = array(
					'date'	=> date('Y-m-d', strtotime('Next '.$this->object->getDayOfWeek())),
					'time'	=> $this->getProperty('start_time')	
				);
				
				$end = array(
					'date'	=> date('Y-m-d', strtotime('Next '.$this->object->getDayOfWeek())),
					'time'	=> $this->getProperty('end_time')
				);

				foreach ($this->getSchedules('day') as $schedule) {
					if ($schedule->isScheduledFor($start, $end)) {
						$this->addFieldError('type', $this->modx->lexicon('narrowcasting.error_broadcast_schedule_exists', array(
							'schedule' => $schedule->toString()
						)));
						
						break;
					}
				}
			} else {
				$start = array(
					'date'	=> $this->getProperty('start_date'),
					'time'	=> $this->getProperty('start_time')	
				);
				
				$end = array(
					'date'	=> $this->getProperty('end_date'),
					'time'	=> $this->getProperty('end_time')
				);

				foreach ($this->getSchedules('date') as $schedule) {
					if ($schedule->isScheduledFor($start, $end)) {
						$this->addFieldError('type', $this->modx->lexicon('narrowcasting.error_broadcast_schedule_exists', array(
							'schedule' => $schedule->toString()
						)));
						
						break;
					}
				}
			}
			
			return parent::beforeSave();
		}
		
		/**
		 * @access public.
		 * @param String $value.
		 * @return Array.
		 */
		public function getSchedules($type) {
			return $this->modx->getCollection('NarrowcastingPlayersSchedules', array(
				'id:!='		=> $this->object->id, 
				'player_id'	=> $this->getProperty('player_id'),
				'type' 		=> $type
			));
		}
	}
	
	return 'NarrowcastingBroadcastSchedulesUpdateProcessor';
	
?>