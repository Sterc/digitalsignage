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
        
		/**
		 * @access public.
		 * @return Boolean.
		 */
        public function needSync() {
	        if (false !== ($export = $this->getLastSync())) {
		        $timestamp = strtotime($export);
		       
		        if (strtotime($this->editedon) <= $timestamp) {
					foreach ($this->getSlides() as $slide) {
						if (strtotime($slide->editedon) >= $timestamp) {
							return true;
						}
					}
					
					return false;
				}
	        }
		    
		    return true;
        }
        
        /**
	     * @access public.
	     * @return Boolean|String.
	     */
        public function getLastSync() {
	        if ($this->getExportPath()) {
		        if (file_exists($this->getExportFile())) {
			        return date('Y-m-d H:i:s', filemtime($this->getExportFile()));
			    }
		    }
		    
		    return false;
        }
        
        /**
	     * @access public.
	     * @param Array $slides.
	     * @return Boolean.
	     */
        public function toExport($slides = array()) {
	        if ($this->getExportPath()) {
		        if ($handle = fopen($this->getExportFile(), 'w')) {
			        fwrite($handle, $this->xpdo->toJSON(array(
			        	'slides' => $slides
			        )));

					fclose($handle);
					
					return true;
				}
			}
			
			return false;
        }
        
        /**
	     * @access public.
	     * @return Array.
	     */
        public function fromExport() {
	        $export = array();
	        
	        if ($this->getExportPath()) {
		        if (file_exists($this->getExportFile())) {
			        if ($handle = fopen($this->getExportFile(), 'r')) {
				    	$slides = fread($handle, filesize($this->getExportFile()));
				    	
				    	if (null !== ($data = $this->xpdo->fromJSON($slides))) {
					    	if (isset($data['slides'])) {
						    	$export = $data['slides'];
					    	}
				    	}
				    	
				    	fclose($handle);
				    }
				}
		    }
		    
		    return $export;
        }
        
        /**
	     * @access public.
	     * @return Boolean|String.
	     */
        public function getExportPath() {
	        $path = dirname(dirname(dirname(__FILE__))).'/export/';

	        if (!is_dir($path) || !is_writable($path)) {
		        return false;
		    }
		    
		    return $path;
        }
        
        /**
	     * @access public.
	     * @return String.
	     */
        public function getExportFile() {
	        return $this->getExportPath().'broadcast-'.$this->id.'.export';
        }
	}

?>
