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

	class DigitalSignagePlayers extends xPDOSimpleObject {
		/**
		 * @access public.
		 * @return String.
		 */
		public function getMode() {
			list($width, $height) = explode('x', $this->resolution);
			
			return $width > $height ? 'landscape' : 'portrait';
		}
		
		/**
		 * @access public.
		 * @param String $value.
		 * @return Array.
		 */
		public function getSchedules($type = null) {
			$schedules = array();

			if (null === $type || in_array($type, array('day', 'date'))) {
				foreach ($this->getMany('getSchedules') as $schedule) {
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
		public function getBroadcasts() {
			$broadcasts = array();

			foreach ($this->getSchedules() as $schedule) {
				if (null !== ($broadcast = $schedule->getOne('getBroadcast'))) {
					if (!isset($broadcasts[$broadcast->id])) {
						if ($broadcast->hasResource()) {
							$broadcasts[$broadcast->id] = $broadcast;
						}
					}
				}
			}

			return $broadcasts;
		}

		/**
		 * @access public.
		 * @return Null|Object.
		 */
		public function getCurrentBroadcast() {
			if ($this->isOnline()) {
				if (null !== ($broadcast = $this->getOne('getCurrentBroadcast'))) {
					if ($broadcast->hasResource()) {
						return $broadcast;
					}
				}
			}

			return null;
		}

		/**
		 * @access public.
		 * @param Integer $broadcast.
		 * @return Boolean.
		 */
		public function isOnline($broadcast = null) {
			$online = strtotime($this->last_online) > time() - (5 * 60);

			if (null !== $broadcast) {
				$online = $online && $broadcast == $this->last_broadcast_id;
			}

			return $online;
		}

        /**
         * @access public.
         * @param Integer $time.
         * @param Integer $broadcast.
         * @return Boolean.
         */
        public function setOnline($time, $broadcast) {
            $restart = 1 == $this->restart;

            if ($restart) {
                $this->set('restart', 0);
            }

        	$this->fromArray(array(
	        	'last_online'		=> date('Y-m-d H:i:s'),
	        	'last_online_time'  => $time,
	        	'last_broadcast_id'	=> $broadcast
        	));

            $this->save();

            return $restart;
        }
	}
	
?>