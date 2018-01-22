<?php

	class DigitalSignageBroadcastsFeeds extends xPDOSimpleObject {
		/**
		 * @access public.
		 * @return Array.
		 */
		public function getSlides() {
			$slides = array();
			
			if (preg_match('/^(http|https)/si', $this->url)) {
				if (false !== ($data = file_get_contents($this->url))) {
	                if (null !== ($data = simplexml_load_string($data))) {
	                    if (isset($data->channel->item)) {
	                        foreach ($data->channel->item as $value) {
		                        $slides[] = $this->toSlide($this->toSlideArray($value));
	                        }
	                    }
	                }
	            }
	        }
            
            return $slides;
		}
		
		/**
		 * @access public.
		 * @param Array $data.
		 * @return Data.
		 */
		public function toSlide($data = array()) {
			if (!isset($data['content'])) {
				if (isset($data['description'])) {
					$data['content'] = $data['description'];
				}
			}

			if (isset($data['image'])) {
			    $data['image'] = urldecode($data['image']);
            }
			
			if (isset($data['enclosure'])) {
				if (isset($data['enclosure']['@attributes']['url'])) {
					$data['image'] = urldecode($data['enclosure']['@attributes']['url']);
				}
				
				unset($data['enclosure']);
			}
			
			return $data;
		}
		
		/**
		 * @access public.
		 * @param Object $data.
		 * @return Array.
		 */
		public function toSlideArray($data) {
			$output = array();
			
			foreach ((array) $data as $key => $value) {
				if (!in_array($key, array('id', 'link'))) {
					if (is_object($value) || is_array($value)) {
						if (!empty($value)) {
							$output[$key] = $this->toSlideArray($value);
						} else {
							$output[$key] = (string) $value;
						}
					} else {
						$output[$key] = (string) $value;
					}
				}
			}
			
			return $output;
		}
	}
	
?>