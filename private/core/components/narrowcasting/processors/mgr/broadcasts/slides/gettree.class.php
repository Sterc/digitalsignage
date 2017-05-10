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
	 
	class NarrowcastingSlidesGetTreeProcessor extends modObjectGetListProcessor {
		/**
		 * @access public.
		 * @var String.
		 */
		public $classKey = 'NarrowcastingSlides';
		
		/**
		 * @access public.
		 * @var Array.
		 */
		public $languageTopics = array('narrowcasting:default');
		
		/**
		 * @access public.
		 * @var String.
		 */
		public $defaultSortField = 'sortindex';
		
		/**
		 * @access public.
		 * @var String.
		 */
		public $defaultSortDirection = 'ASC';
		
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
		 * @access public.
		 * @param Object $c.
		 * @return Object.
		 */
		public function prepareQueryBeforeCount(xPDOQuery $c) {
			$c->select($this->modx->getSelectColumns('NarrowcastingSlides', 'NarrowcastingSlides'));
			$c->select($this->modx->getSelectColumns('NarrowcastingBroadcastsSlides', 'NarrowcastingBroadcastsSlides', 'c_', array('id')));
			
			$c->innerjoin('NarrowcastingBroadcastsSlides', 'NarrowcastingBroadcastsSlides', array('NarrowcastingSlides.id = NarrowcastingBroadcastsSlides.slide_id'));
			
			$broadcast = $this->getProperty('broadcast_id');
			
			if (!empty($broadcast)) {
				$c->where(array(
					'NarrowcastingBroadcastsSlides.broadcast_id' => $broadcast
				));
			}

			return $c;
		}
		
		/**
		 * @access public.
		 * @param Object $query.
		 * @return Array.
		 */
		public function prepareRow(xPDOObject $object) {
			$class 	= array();
			$icon 	= '';
			
			if (0 == $object->published) {
				$class[] = 'unpublished';
			}
			
			if (null !== ($type = $object->getOne('getSlideType'))) {
				if (!empty($type->icon)) {
					$icon = 'icon-'.$type->icon;
				}
			}
			
			return array(
				'id' 		=> 'n_slide:'.$object->id.'_id:'.$object->c_id,
	            'text' 		=> $object->name,
	            'cls' 		=> implode(' ', $class),
	            'iconCls' 	=> empty($icon) ? 'icon-file' : $icon,
	            'loaded'	=> true,
	            'leaf'		=> true,
	            'data' 		=> array_merge($object->toArray(), array(
	            	'c_id'		=> $object->c_id
	            )),
	            'pk'		=> $this->getProperty('broadcast_id')
	        );
		}
		
		/**
		 * @access public.
		 * @param Array $array.
		 * @param Boolean $count.
		 * @return String.
		 */
		public function outputArray($array) {
        	return $this->modx->toJSON($array);
    	}
	}

	return 'NarrowcastingSlidesGetTreeProcessor';
	
?>