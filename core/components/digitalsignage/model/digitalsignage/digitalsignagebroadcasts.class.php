<?php

    class DigitalSignageBroadcasts extends xPDOSimpleObject {
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
		 * @param String $type.
		 * @return Array.
		 */
		public function getFeeds($type = 'all') {
			$feeds = array();

			foreach ($this->getMany('getFeeds') as $feed) {
				if (1 == $feed->published) {
                    if ('content' == $type) {
                        if (0 == $feed->frequency) {
                            $feeds[] = $feed;
                        }
                    } else if ('specials' == $type) {
                        if (1 <= $feed->frequency) {
                            $feeds[] = $feed;
                        }
                    } else {
                        $feeds[] = $feed;
                    }
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
         * @return Array.
         */
        public function getTickerItems() {
	    	$items = array();

		    if (preg_match('/^(http|https)/si', $this->ticker_url)) {
			    if (false !== ($data = file_get_contents($this->ticker_url))) {
	                if (null !== ($data = simplexml_load_string($data))) {
	                    if (isset($data->channel->item)) {
	                        foreach ($data->channel->item as $value) {
		                        $items[] = $value;
	                        }
	                    }
	                }
	            }
		    }
	    	
	    	return $items;
        }
        
        /**
         * @access public.
         * @return Boolean.
         */
        public function needSync() {
            if (false !== ($export = $this->getLastSync())) {
                $timestamp = strtotime($export);

                if (strtotime($this->get('editedon')) <= $timestamp) {
                    foreach ($this->getSlides() as $slide) {
                        if (strtotime($slide->get('editedon')) >= $timestamp) {
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
            return $this->getExportPath().'broadcast-'.$this->get('id').'.export';
        }
    }

?>
