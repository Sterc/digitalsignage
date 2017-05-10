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
	 
	class NarrowcastingBroadcastsRemoveProcessor extends modObjectRemoveProcessor {
		/**
		 * @acces public.
		 * @var String.
		 */
		public $classKey = 'NarrowcastingBroadcasts';
		
		/**
		 * @acces public.
		 * @var Array.
		 */
		public $languageTopics = array('narrowcasting:default');
		
		/**
		 * @acces public.
		 * @var String.
		 */
		public $objectType = 'narrowcasting.broadcasts';
		
		/**
		 * @acces public.
		 * @var Object.
		 */
		public $narrowcasting;
		
		/**
		 * @acces public.
		 * @return Mixed.
		 */
		public function initialize() {
			$this->narrowcasting = $this->modx->getService('narrowcasting', 'Narrowcasting', $this->modx->getOption('narrowcasting.core_path', null, $this->modx->getOption('core_path').'components/narrowcasting/').'model/narrowcasting/');

			return parent::initialize();
		}
		
		/**
		 * @acces public.
		 * @return Mixed.
		 */
		public function beforeRemove() {
			$response = $this->modx->runProcessor('resource/delete', array(
				'id' => $this->object->resource_id
			));

			return parent::beforeRemove();
		}
		
		/**
		 * @acces public.
		 * @return Mixed.
		 */
		public function afterRemove() {
			foreach ($this->object->getMany('getSlides') as $slide) {
				$slide->remove();
			}
			
			foreach ($this->object->getMany('getSchedules') as $schedulde) {
				$schedulde->remove();
			}

			return parent::afterRemove();
		}
	}
	
	return 'NarrowcastingBroadcastsRemoveProcessor';
	
?>