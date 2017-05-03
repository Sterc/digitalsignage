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

	class NarrowcastingBroadcastSchedulesCreateProcessor extends modObjectCreateProcessor {
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
		 * @access public.
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
			if (null !== ($object = $this->modx->newObject('NarrowcastingPlayersSchedules'))) {
				$object->fromArray($this->getProperties());

				if ($object->is('day')) {
					$start = array(
						'date'	=> date('Y-m-d', strtotime('Next '.$object->getDayOfWeek())),
						'time'	=> $object->isEmpty($this->getProperty('start_time'), 'time', '00:00:00')	
					);
					
					$end = array(
						'date'	=> date('Y-m-d', strtotime('Next '.$object->getDayOfWeek())),
						'time'	=> $object->isEmpty($this->getProperty('end_time'), 'time', '00:00:00')
					);

					foreach ($this->getSchedules('day') as $schedule) {
						if ($schedule->isScheduledFor($start, $end)) {
							$this->addFieldError('type', $this->modx->lexicon('narrowcasting.error_broadcast_schedule_exists', array(
								'schedule' => $schedule->toString()
							)));
						}
					}
				} else {
					$start = array(
						'date'	=> $object->isEmpty($this->getProperty('start_date'), 'date', '0000-00-00'),
						'time'	=> $object->isEmpty($this->getProperty('start_time'), 'time', '00:00:00')	
					);
					
					$end = array(
						'date'	=> $object->isEmpty($this->getProperty('end_date'), 'date', '0000-00-00'),
						'time'	=> $object->isEmpty($this->getProperty('end_time'), 'time', '00:00:00')
					);
					
					print_r(array(
						'start' => $start,
						'end'	=> $end
					));
					
					foreach ($this->getSchedules('date') as $schedule) {
						if ($schedule->isScheduledFor($start, $end)) {
							
						}
					}
					
					$this->addFieldError('broadcast_id', 'FOUT');
				}

				/*$type = $this->getProperty('type');
				
				if ('day' == $type) {
					$start 	= strtotime(date('d-m-Y '.$startTime, strtotime('Next '.date('l', strtotime('Sunday +'.$this->getProperty('day').' Days')))));
					$end 	= strtotime(date('d-m-Y '.$endTime, strtotime('Next '.date('l', strtotime('Sunday +'.$this->getProperty('day').' Days')))));
					
					if ($this->getProperty('entire_day') || '00:00:00' == $endTime) {
						$end = $end + (60 * 60 * 24);
					}
	
					foreach ($this->getSchedules($type) as $schedule) {
						if ($schedule->isScheduledFor($start, $end)) {
							$this->addFieldError('type', $this->modx->lexicon('narrowcasting.error_broadcast_schedule_exists', array(
								'schedule' => $schedule->toString()
							)));
						}
					}
				} else if ('date' == $type) {
					foreach ($this->getSchedules($type) as $schedule) {
						
					}
				}*/
				
				
			} else {
				$this->addFieldError('broadcast_id', 'FOUT');
			}

			
			/*if (null !== ($type = $this->getProperty('type'))) {
				$startTime  = $this->getProperty('start_time');
				$endTime 	= $this->getEmptyTime($this->getProperty('end_time'));
				
				$criterea = array(
					'broadcast_id'	=> $this->getProperty('broadcast_id'),
					'type'			=> $this->getProperty('type')	
				);
					
				if ('day' == $type) {
					$start 	= strtotime(date('d-m-Y '.$startTime));
					$end 	= strtotime(date('d-m-Y '.$endTime));
							
					$criterea = array_merge($criterea, array(
						'day' => $this->getProperty('day')	
					));

					foreach ($this->modx->getCollection($this->classKey, $criterea) as $schedule) {
						if ('00:00:00' == $schedule->start_time && '00:00:00' == $schedule->end_time) {
							$this->addFieldError('day', $this->modx->lexicon('narrowcasting.error_broadcast_schedule_day_entire_day'));
						} else {
							$scheduleStart 	= strtotime(date('d-m-Y '.$schedule->start_time));
							$scheduleEnd	= strtotime(date('d-m-Y '.$this->getEmptyTime($schedule->start_time)));

							if ($scheduleStart < $start && $scheduleEnd > $start) {
								if (null !== $this->getProperty('entire_day')) {
									$this->addFieldError('day', $this->modx->lexicon('narrowcasting.error_broadcast_schedule_day_time'));
								} else {
									$this->addFieldError('start_time', $this->modx->lexicon('narrowcasting.error_broadcast_schedule_day_time'));
								}
							}
							
							if ($scheduleStart < $end && $scheduleEnd > $end) {
								if (null !== $this->getProperty('entire_day')) {
									$this->addFieldError('day', $this->modx->lexicon('narrowcasting.error_broadcast_schedule_day_time'));
								} else {
									$this->addFieldError('end_time', $this->modx->lexicon('narrowcasting.error_broadcast_schedule_day_time'));
								}
							}
						}
					}
				} else if ('date' == $type) {
					$start 	= strtotime(date($this->getProperty('start_date').' 00:00:00'));
					$end 	= strtotime(date($this->getEmptyDate($this->getProperty('end_date'), $this->getProperty('start_date')).' 00:00:00'));
					
					
					foreach ($this->modx->getCollection($this->classKey, $criterea) as $schedule) {
						$scheduleStart 	= strtotime(date($schedule->start_date.' 00:00:00'));
						$scheduleEnd	= strtotime(date($this->getEmptyDate($schedule->end_date, $schedule->start_date).' 00:00:00'));
						
						if ($scheduleStart <= $start && $scheduleEnd >= $start) {
							if (null !== $this->getProperty('entire_day')) {
								$this->addFieldError('start_date', $this->modx->lexicon('narrowcasting.error_broadcast_schedule_date_time'));
							} else {
								$this->addFieldError('start_date', $this->modx->lexicon('narrowcasting.error_broadcast_schedule_date_time'));
							}
						}
						
						if ($scheduleStart <= $end && $scheduleEnd >= $end) {
							if (null !== $this->getProperty('entire_day')) {
								$this->addFieldError('end_date', $this->modx->lexicon('narrowcasting.error_broadcast_schedule_date_time'));
							} else {
								$this->addFieldError('end_date', $this->modx->lexicon('narrowcasting.error_broadcast_schedule_date_time'));
							}
						}
					}
				}
			}*/
			
			return parent::beforeSave();
		}
		
		/**
		 * @access public.
		 * @param String $value.
		 * @return Array.
		 */
		public function getSchedules($type) {
			return $this->modx->getCollection('NarrowcastingPlayersSchedules', array(
				'player_id'	=> $this->getProperty('player_id'),
				'type' 		=> $type
			));
		}
	}
	
	return 'NarrowcastingBroadcastSchedulesCreateProcessor';
	
?>