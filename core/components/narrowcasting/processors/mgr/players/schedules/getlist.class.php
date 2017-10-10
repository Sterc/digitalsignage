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
	 
	class NarrowcastingPlayersSchedulesGetListProcessor extends modObjectGetListProcessor {
		/**
		 * @access public.
		 * @var String.
		 */
		public $classKey = 'NarrowcastingPlayersSchedules';
		
		/**
		 * @access public.
		 * @var Array.
		 */
		public $languageTopics = array('narrowcasting:default');
		
		/**
		 * @access public.
		 * @var String.
		 */
		public $defaultSortField = 'id';
		
		/**
		 * @access public.
		 * @var String.
		 */
		public $defaultSortDirection = 'DESC';
		
		/**
		 * @access public.
		 * @var String.
		 */
		public $objectType = 'narrowcasting.players';
		
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
			$c->select($this->modx->getSelectColumns('NarrowcastingPlayersSchedules', 'NarrowcastingPlayersSchedules'));
			$c->select($this->modx->getSelectColumns('NarrowcastingBroadcasts', 'NarrowcastingBroadcasts', null, array('resource_id', 'color')));
			$c->select($this->modx->getSelectColumns('modResource', 'modResource', null, array('pagetitle', 'template')));
			
			$c->innerjoin('NarrowcastingBroadcasts', 'NarrowcastingBroadcasts', array('NarrowcastingBroadcasts.id = NarrowcastingPlayersSchedules.broadcast_id'));
			$c->innerjoin('modResource', 'modResource', array('modResource.id = NarrowcastingBroadcasts.resource_id'));
			
			$player = $this->getProperty('player_id');
			
			if (!empty($player)) {
				$c->where(array(
					'NarrowcastingPlayersSchedules.player_id' => $player
				));
			}
			
			$broadcast = $this->getProperty('broadcast_id');
			
			if (!empty($broadcast)) {
				$c->where(array(
					'NarrowcastingPlayersSchedules.broadcast_id' => $broadcast
				));
			}

			return $c;
		}
		
		/**
		 * @access public.
		 * @param Object $object.
		 * @return Array.
		 */
		public function prepareRow(xPDOObject $object) {
			return array_merge($object->toArray(), array(
				'broadcast'				=> array(
					'name'					=> $object->pagetitle,
					'color'					=> $object->color
				),
				'start_time' 			=> date($this->modx->getOption('manager_time_format'), strtotime($object->start_time)),
				'start_date' 			=> date($this->modx->getOption('manager_date_format'), strtotime($object->start_date)),
				'end_time' 				=> date($this->modx->getOption('manager_time_format'), strtotime($object->end_time)),
				'end_date' 				=> date($this->modx->getOption('manager_date_format'), strtotime($object->end_date)),
				'type_formatted' 		=> $this->modx->lexicon('narrowcasting.schedule_'.$object->type),
				'date_formatted' 		=> $object->toString(),
				'entire_day'			=> $object->isEntireDay()
			));
		}
	}

	return 'NarrowcastingPlayersSchedulesGetListProcessor';
	
?>