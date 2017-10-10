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
	 
	class NarrowcastingBroadcastsFeeds extends xPDOSimpleObject {
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