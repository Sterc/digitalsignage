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
	 
	class NarrowcastingSlideTypesDataGetListProcessor extends modProcessor {
		/**
		 * @access public.
		 * @var String.
		 */
		public $classKey = 'NarrowcastingSlidesTypes';
		
		/**
		 * @access public.
		 * @var Array.
		 */
		public $languageTopics = array('narrowcasting:default', 'narrowcasting:slides');
		
		/**
		 * @access public.
		 * @var String.
		 */
		public $defaultSortField = 'key';
		
		/**
		 * @access public.
		 * @var String.
		 */
		public $defaultSortDirection = 'ASC';
		
		/**
		 * @access public.
		 * @var String.
		 */
		public $objectType = 'narrowcasting.slidestypes';
		
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

            $this->setDefaultProperties(array(
                'start'     => 0,
                'limit'     => 20,
                'sort'      => $this->defaultSortField,
                'dir'       => $this->defaultSortDirection,
                'combo'     => false,
                'query'     => ''
            ));
			
			return parent::initialize();
		}
		

		/**
		 * @access public.
		 * @return Array.
		 */
		public function process() {
		    if (null !== ($object = $this->modx->getObject($this->classKey, $this->getProperty('id')))) {
		        $output = array();
                $data   = unserialize($object->data);
                $query  = $this->getProperty('query');

                if (!is_array($data)) {
                    $data = array();
                }

                if ('key' == $this->getProperty('sort')) {
                    if ('DESC' == strtoupper($this->getProperty('dir'))) {
                        krsort($data);
                    } else {
                        ksort($data);
                    }
                }

		        foreach ($data as $key => $value) {
                    if (!empty($query)) {
                        if (preg_match('/'.$query.'/', $key)) {
                            $output[] = array_merge(array(
                                'key' => $key
                            ), $value);
                        }
                    } else {
                        $output[] = array_merge(array(
                            'key' => $key
                        ), $value);
                    }
                }

		        return $this->outputArray(array_slice($output, $this->getProperty('start'), $this->getProperty('limit')), count($output));
            }

			return $this->failure('Mislukt');
		}
	}

	return 'NarrowcastingSlideTypesDataGetListProcessor';
	
?>