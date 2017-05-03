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
	 
	class NarrowcastingBroadcasts extends xPDOSimpleObject {
		/**
		 * @acccess public.
		 * @var Null|Object.
		 */
		public $resource = null;

		/**
		 * @access public.
		 * @return Null|Object.
		 */
		public function getResource() {
			if (null === $this->resource) {
				$this->resource = $this->getOne('modResource');
			}
			
			return $this->resource;
		}
		
		/**
		 * @access public.
		 * @return Boolean.
		 */
		public function hasResource() {
			return null === $this->getResource() ? false : true;	
		}
		
		/**
		 * @access public.
		 * @return Array|Boolean.
		 */
		public function isScheduled() {
            foreach ($this->getSchedules('date') as $schedule) {
	            if ($schedule->isScheduled()) {
		        	return array(
						'start'		=> date('Y-m-d H:i:s', $schedule->getStart()),
						'end'		=> date('Y-m-d H:i:s', $schedule->getEnd())	
					);
		        }
            }
		                 
			foreach ($this->getSchedules('day') as $schedule) {
				if ($schedule->isScheduled()) {
					return array(
						'start'		=> date('Y-m-d H:i:s', $schedule->getStart()),
						'end'		=> date('Y-m-d H:i:s', $schedule->getEnd())	
					);
				}
			}

			return false;
		}
		
		/**
		 * @access public.
		 * @param String $value.
		 * @return Array.
		 */
		public function getSchedules($type = null) {
			$schedules = array();
			
			if (null === $type || in_array($type, array('day', 'date'))) {
				foreach ($this->getMany('NarrowcastingSchedules') as $schedule) {
					if (null === $type || $type == $schedule->type) {
						$schedules[] = $schedule;
					}
				}
			}
			
			return $schedules;
		}
		
		/**
		 * @access public.
		 * @return Array.
		 */
		public function getPlayers() {
			$players = array();
			
			foreach ($this->getSchedules() as $schedule) {
				if (null !== ($player = $schedule->getOne('NarrowcastingPlayers'))) {
					if (!isset($players[$player->id])) {
						$players[$player->id] = $player;
					}
				}
			}
			
			return $players;
		}
	}
	
?>