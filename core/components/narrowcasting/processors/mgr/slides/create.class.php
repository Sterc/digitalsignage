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

	class NarrowcastingSlidesCreateProcessor extends modObjectCreateProcessor {
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
			
			if (null === $this->getProperty('published')) {
				$this->setProperty('published', 0);
			}
			
			return parent::initialize();
		}
		
		/**
		 * @access public.
		 * @return Mixed.
		 */
		public function beforeSave() {
			$data = array();

			foreach ($this->getProperties() as $key => $value) {
				if (false !== strstr($key, 'data_')) {
					$data[substr($key, 5, strlen($key))] = $value;
				}
			}

			$this->object->fromArray('data', serialize($data));

            if (null === ($broadcasts = $this->getProperty('broadcasts'))) {
                $broadcasts = array();
            }

            $currentBroadcastIDs = $this->getBroadcasts(null, true);

            foreach ($broadcasts as $key => $broadcastID) {
                $broadcastIDs = $this->getBroadcasts($broadcastID);

                if (0 < count($broadcastIDs)) {
                    $sortIndex = end(array_keys($broadcastIDs));
                } else {
                    $sortIndex = 0;
                }

                if (0 == count($this->getBroadcasts($broadcastID, true))) {
                    if (null !== ($broadcast = $this->modx->newObject('NarrowcastingBroadcastsSlides'))) {
                        $broadcast->fromArray(array(
                            'broadcast_id'  => $broadcastID,
                            'sortindex'     => $sortIndex + 1
                        ));

                        $this->object->addMany($broadcast);
                    }
                }
            }

            foreach ($currentBroadcastIDs as $broadcast) {
                if (!in_array($broadcast->get('broadcast_id'), $broadcasts)) {
                    $broadcast->remove();
                }
            }

			return parent::beforeSave();
		}

        /**
         * @access protected.
         * @param Int $id.
         * @param Boolean $unique;
         * @return Array.
         */
        protected function getBroadcasts($id = null, $unique = false) {
            $broadcasts = array();

            $c = array();

            if (null !== $id) {
                $c = array_merge($c, array(
                    'broadcast_id' => $id
                ));
            }

            if ($unique) {
                $c = array_merge($c, array(
                    'slide_id' => $this->object->get('id')
                ));
            }

            foreach ($this->modx->getCollection('NarrowcastingBroadcastsSlides', $c) as $broadcast) {
                if (null !== $id) {
                    $broadcasts[$broadcast->get('sortindex')] = $broadcast;
                } else {
                    $broadcasts[] = $broadcast;
                }
            }

            ksort($broadcasts);

            return $broadcasts;
        }
	}
	
	return 'NarrowcastingSlidesCreateProcessor';
	
?>