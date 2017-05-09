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
         * Check if schedule is scheduled.
         *
         * @access public
         * @param int $player
         *
         * @return array|bool
         */
        public function isScheduled($player = null) {
            foreach ($this->getSchedules('date', $player) as $schedule) {
                $start = [
                    'date' => date('Y-m-d', strtotime($schedule->getFirstDate())),
                    'time' => date('H:i:s', strtotime($schedule->getFirstDate()))
                ];

                $end = [
                    'date' => date('Y-m-d', strtotime($schedule->getLastDate())),
                    'time' => date('H:i:s', strtotime($schedule->getLastDate()))
                ];

                if ($schedule->isScheduledFor($start, $end)) {
                    return array(
                        'start' => date('Y-m-d H:i:s', $schedule->getFirstDate()),
                        'end'   => date('Y-m-d H:i:s', $schedule->getLastDate())
                    );
                }
            }

            foreach ($this->getSchedules('day', $player) as $schedule) {
                if ($schedule->get('day') !== (int) date('N')) {
                    continue;
                }

                return array(
                    'start' => date('Y-m-d H:i:s', $schedule->getFirstDate()),
                    'end'   => date('Y-m-d H:i:s', $schedule->getLastDate())
                );
            }

            return false;
        }

        /**
         * Get broadcast schedules.
         *
         * @access public
         * @param string $type
         * @param int $player
         *
         * @return array
         */
        public function getSchedules($type = null, $player = null) {
            $schedules = array();

            if (null === $type || in_array($type, array('day', 'date'))) {
                foreach ($this->getMany('NarrowcastingSchedules') as $schedule) {
                    if (
                        ($type === null || $type === $schedule->type) &&
                        ($player === null || $player === $schedule->player_id)
                    ) {
                        $schedules[] = $schedule;
                    }
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
		 * @return Array.
		 */
		public function getSlides() {
			$slides = array();
			
			//foreach ($this->getMany('getSlides') as $slide) {
				/*if (1 == $slide->published) {
					$slides[] = $slide;
				}*/
			//}
			
			return $slides;
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