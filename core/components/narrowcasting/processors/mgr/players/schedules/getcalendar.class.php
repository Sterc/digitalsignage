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
	 
	class NarrowcastingPlayersSchedulesGetCalendarProcessor extends modProcessor {
		/**
		 * @access public.
		 * @var String.
		 */
		public $classKey = 'NarrowcastingPlayersSchedules';
		
		/**
		 * @access public.
		 * @var String.
		 */
		public $objectType = 'narrowcasting.players';
		
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
		public function process() {
			$data 	= array();
			$dates 	= array();
			
			foreach ($this->getSchedules('date') as $schedule) {
				$start = array(
					'date'	=> $schedule->start_date,
					'time'	=> $schedule->start_time	
				);
				
				$end = array(
					'date'	=> $schedule->end_date,
					'time'	=> $schedule->end_time	
				);
			
				foreach ($schedule->getRange($start, $end) as $date) {
					$data[] = array(
						'id'		=> uniqid(),
						'cid'		=> $schedule->broadcast_id,
						'title'		=> $schedule->pagetitle,
						'start'		=> date('Y-m-d\TH:i:sP', strtotime($date['format_start'])),
						'end'		=> date('Y-m-d\TH:i:sP', strtotime($date['format_end'])),
						'type'		=> 'date'
					);
				}
			}

			foreach ($this->getSchedules('day') as $schedule) {
				$start = array(
					'date'	=> $this->getProperty('start'),
					'time'	=> $schedule->start_time	
				);
				
				$end = array(
					'date'	=> $this->getProperty('end'),
					'time'	=> $schedule->end_time		
				);
				
				foreach ($schedule->getRange($start, $end) as $date) {
					$data[] = array(
						'id'		=> uniqid(),
						'cid'		=> $schedule->broadcast_id,
						'title'		=> $schedule->pagetitle,
						'start'		=> date('Y-m-d\TH:i:sP', strtotime($date['format_start'])),
						'end'		=> date('Y-m-d\TH:i:sP', strtotime($date['format_end'])),
						'type'		=> 'day'
					);
				}
			}
			
			$sort = array();
			
			foreach ($data as $key => $value) {
			    $sort[$key] = $value['type'];
			}
			
			array_multisort($sort, SORT_DESC, $data);
			
			return $this->outputArray($data);
		}
		
		/**
		 * @access public.
		 * @param String $type.
		 * @return Array.
		 */
		public function getSchedules($type) {
			$c = $this->modx->newQuery('NarrowcastingPlayersSchedules');
			
			$c->select($this->modx->getSelectColumns('NarrowcastingPlayersSchedules', 'NarrowcastingPlayersSchedules'));
			$c->select($this->modx->getSelectColumns('NarrowcastingBroadcasts', 'NarrowcastingBroadcasts', null, array('resource_id')));
			$c->select($this->modx->getSelectColumns('modResource', 'modResource', null, array('pagetitle')));
			
			$c->innerjoin('NarrowcastingBroadcasts', 'NarrowcastingBroadcasts', array('NarrowcastingBroadcasts.id = NarrowcastingPlayersSchedules.broadcast_id'));
			$c->innerjoin('modResource', 'modResource', array('modResource.id = NarrowcastingBroadcasts.resource_id'));
			
			$c->where(array(
				'NarrowcastingPlayersSchedules.player_id' 	=> $this->getProperty('player_id'),
				'NarrowcastingPlayersSchedules.type'		=> $type
			));
			
			return $this->modx->getCollection('NarrowcastingPlayersSchedules', $c);
		}
	}

	return 'NarrowcastingPlayersSchedulesGetCalendarProcessor';
	
?>