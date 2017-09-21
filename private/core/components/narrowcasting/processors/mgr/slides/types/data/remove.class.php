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
	 
	class NarrowcastingSlideTypesDataRemoveProcessor extends modProcessor {
		/**
		 * @acces public.
		 * @var String.
		 */
		public $classKey = 'NarrowcastingSlidesTypes';
		
		/**
		 * @acces public.
		 * @var Array.
		 */
		public $languageTopics = array('narrowcasting:default');
		
		/**
		 * @acces public.
		 * @var String.
		 */
		public $objectType = 'narrowcasting.slidestypes';
		
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
         * @access public.
         * @return Mixed.
         */
        public function process() {
            if (null !== ($object = $this->modx->getObject($this->classKey, $this->getProperty('id')))) {
                $data = unserialize($object->data);

                if (isset($data[$this->getProperty('key')])) {
                    unset($data[$this->getProperty('key')]);
                }

                $object->fromArray(array(
                    'data' => serialize($data)
                ));

                if (!$object->save()) {
                    $this->addFieldError('key', $this->modx->lexicon('narrowcasting.error_slide_type_not_exists'));
                } else {
                    return $this->success('', $object);
                }
            } else {
                $this->addFieldError('key', $this->modx->lexicon('narrowcasting.error_slide_type_not_exists'));
            }

            return $this->failure();
        }
	}
	
	return 'NarrowcastingSlideTypesDataRemoveProcessor';

?>