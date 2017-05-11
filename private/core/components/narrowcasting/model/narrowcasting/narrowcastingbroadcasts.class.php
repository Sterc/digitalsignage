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
         * @param Null|Integer $player.
         * @return Array|Boolean.
         */
        public function isScheduled($player = null) {
            foreach ($this->getSchedules('date', $player) as $schedule) {
	            $timestamp = array(
		        	'date'	=> date('Y-m-d'),
		        	'time'	=> date('H:i:s')
	            );

	            if ($schedule->isScheduledFor($timestamp, $timestamp)) {
		            return $schedule;
	            }
            }

            foreach ($this->getSchedules('day', $player) as $schedule) {
	            $timestamp = array(
		        	'date'	=> date('Y-m-d'),
		        	'time'	=> date('H:i:s')
	            );

	            if ($schedule->isScheduledFor($timestamp, $timestamp)) {
		            return $schedule;
	            }
            }

            return false;
        }

        /**
         * @access public.
         * @param Null|String $type.
         * @param Null|Integer $player.
         * @return Array.
         */
        public function getSchedules($type = null, $player = null) {
            $schedules = array();

            if (null === $type || in_array($type, array('day', 'date'))) {
                foreach ($this->getMany('getSchedules') as $schedule) {
                    if ((null == $type || $type === $schedule->type) && (null === $player || $player === $schedule->player_id)) {
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
		public function getSlides() {
			$slides = array();

			foreach ($this->getMany('getSlides') as $slide) {
			    $sortOrder = $slide->get('sortindex');

				if (null !== ($slide = $slide->getOne('getSlide'))) {
					if (1 == $slide->published) {
						$slides[$sortOrder] = $slide;
					}
				}
			}

			ksort($slides);

			return $slides;
		}

		/**
		 * @access public.
		 * @return Array.
		 */
		public function getFeeds() {
			$feeds = array();

			foreach ($this->getMany('getFeeds') as $feed) {
				if (1 == $feed->published) {
					$feeds[] = $feed;
				}
			}

			return $feeds;
		}

		/**
         * @access public.
         * @return Array.
         */
        public function getPlayers() {
            $players = array();

            foreach ($this->getMany('getSchedules') as $schedule) {
	            if (null !== ($player = $schedule->getOne('getPlayer'))) {
		            if (!isset($players[$player->id])) {
			            $players[$player->id] = $player;
			        }
		        }
	        }

            return $players;
        }
	}

?>
