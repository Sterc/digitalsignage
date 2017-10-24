<?php

	/**
	 * DigitalSignage
	 *
	 * Copyright 2016 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
	 *
	 * DigitalSignage is free software; you can redistribute it and/or modify it under
	 * the terms of the GNU General Public License as published by the Free Software
	 * Foundation; either version 2 of the License, or (at your option) any later
	 * version.
	 *
	 * DigitalSignage is distributed in the hope that it will be useful, but WITHOUT ANY
	 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
	 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
	 *
	 * You should have received a copy of the GNU General Public License along with
	 * DigitalSignage; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
	 * Suite 330, Boston, MA 02111-1307 USA
	 */
	 
	class DigitalSignageSlidesTypesGetListProcessor extends modObjectGetListProcessor {
		/**
		 * @access public.
		 * @var String.
		 */
		public $classKey = 'DigitalSignageSlidesTypes';
		
		/**
		 * @access public.
		 * @var Array.
		 */
		public $languageTopics = array('digitalsignage:default', 'digitalsignage:slides');
		
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
		public $objectType = 'digitalsignage.slidestypes';
		
		/**
		 * @access public.
		 * @var Object.
		 */
		public $digitalsignage;
		
		/**
		 * @access public.
		 * @return Mixed.
		 */
		public function initialize() {
			$this->digitalsignage = $this->modx->getService('digitalsignage', 'DigitalSignage', $this->modx->getOption('digitalsignage.core_path', null, $this->modx->getOption('core_path').'components/digitalsignage/').'model/digitalsignage/');
			
			return parent::initialize();
		}
		
		/**
		 * @access public.
		 * @param Object $object.
		 * @return Array.
		 */
		public function prepareRow(xPDOObject $object) {
			$array = array_merge($object->toArray(), array(
				'name_formatted' 			=> $object->name,
				'description_formatted' 	=> $object->description,
				'data'						=> unserialize($object->data)
			));
			
			if (empty($object->name)) {
				$translationKey = 'digitalsignage.slide_'.$object->key;
				
				if ($translationKey !== ($translation = $this->modx->lexicon($translationKey))) {
					$array['name_formatted'] = $translation;
				}
			}
			
			if (empty($object->description)) {
				$translationKey = 'digitalsignage.slide_'.$object->key.'_desc';
				
				if ($translationKey !== ($translation = $this->modx->lexicon($translationKey))) {
					$array['description_formatted'] = $translation;
				}
			}

			return $array;
		}
	}

	return 'DigitalSignageSlidesTypesGetListProcessor';
	
?>