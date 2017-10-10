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

	class NarrowcastingPlayersCreateProcessor extends modObjectCreateProcessor {
		/**
		 * @access public.
		 * @var String.
		 */
		public $classKey = 'NarrowcastingPlayers';
		
		/**
		 * @access public.
		 * @var Array.
		 */
		public $languageTopics = array('narrowcasting:default');
		
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

			if (null === ($key = $this->getProperty('key'))) {
				$unique = false;
				
				while (!$unique) {
					$criterea = array(
						'key' => $this->generatePlayerKey()
					);
					
					if (null === $this->modx->getObject('NarrowcastingPlayers', $criterea)) {
						$this->setProperty('key', $criterea['key']);
						
						$unique = true;
					}
				}
			}
			
			return parent::initialize();
		}
		
		/**
		 * @access public.
		 * @return Mixed.
		 */
		public function beforeSave() {
			if (!preg_match('/^(\d+)x(\d+)$/', $this->getProperty('resolution'))) {
				$this->addFieldError('resolution', $this->modx->lexicon('narrowcasting.error_player_resolution'));
			}
			
			return parent::beforeSave();
		}
		
		/**
		 * @access public.
		 * @return String.
		 */
		public function generatePlayerKey() {
			$key = '';
			$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			
			for ($i = 0; $i < 12; $i++) {
				$key .= $characters[rand(0, strlen($characters) - 1)];
			}

			return implode(':', str_split($key, 3));
		}
	}
	
	return 'NarrowcastingPlayersCreateProcessor';
	
?>