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
	 
	class NarrowcastingSlidesSortProcessor extends modProcessor {
		/**
		 * @access public.
		 * @var String.
		 */
		public $classKey = 'NarrowcastingBroadcastsSlides';
		
		/**
		 * @access public.
		 * @var Array.
		 */
		public $languageTopics = array('narrowcasting:default');
		
		/**
		 * @access public.
		 * @var String.
		 */
		public $objectType = 'narrowcasting.slides';
		
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
		 * @acccess public.
		 * @return Mixed.
		 */
		public function process() {
			$data = urldecode($this->getProperty('data', ''));
			
			if (empty($data)) {
				$this->failure($this->modx->lexicon('invalid_data'));
			}
			
			$data = $this->modx->fromJSON($data);
			
			if (empty($data)) {
				$this->failure($this->modx->lexicon('invalid_data'));
			}
			
			$c = array(
				'id' => $this->getProperty('source_pk')	
			);

			if (null !== ($broadcast = $this->modx->getObject('NarrowcastingBroadcasts', $c))) {
				$index = 1;
				$nodes = array();
				
				foreach ($data as $key => $children) {
					$node = $this->getNodeID($key);
					
					if (empty($children)) {
						if (isset($node['id'])) {
							$c = array(
								'id' => $node['id']	
							);
							
							if (null !== ($object = $this->modx->getObject('NarrowcastingBroadcastsSlides', $c))) {
								$object->fromArray(array(
									'broadcast_id'	=> $broadcast->id,
									'slide_id'		=> $node['slide'],
									'sortindex' 	=> $index
								));
								
								if ($object->save()) {
									$nodes[$key] = array(
										'id'		=> 'n_slide:'.$object->slide_id.'_id:'.$object->id,
										'data'		=> $object->toArray()
									);
								}
								
								
							}
						} else {
							if (null !== ($object = $this->modx->newObject('NarrowcastingBroadcastsSlides'))) {
								$object->fromArray(array(
									'broadcast_id'	=> $broadcast->id,
									'slide_id'		=> $node['slide'],
									'sortindex'		=> $index
								));
								
								if ($object->save()) {
									$nodes[$key] = array(
										'id'		=> 'n_slide:'.$object->slide_id.'_id:'.$object->id,
										'data'		=> $object->toArray()
									);
								}
							}
						}
						
						$index++;
					}
				}
				
				$broadcast->fromArray(array(
					'hash' => time()
				));
				
				$broadcast->save();
			}
	
			return $this->outputArray($nodes);
		}
		
		/**
		 * @access public.
		 * @param String $node.
		 * @return Array.
		 */
		public function getNodeID($node) {
			$data = array();

			foreach (explode('_', str_replace('n_', '', $node)) as $part) {
				list($type, $value) = explode(':', $part, 2);
				
				$data[$type] = $value;
			}
			
			return $data;
		}
	}

	return 'NarrowcastingSlidesSortProcessor';
	
?>