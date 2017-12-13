<?php

    class DigitalSignageSlideTypesGetListProcessor extends modObjectGetListProcessor {
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
        public $primaryKeyField = 'key';

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

            $this->setDefaultProperties(array(
                'dateFormat' => $this->modx->getOption('manager_date_format') .', '. $this->modx->getOption('manager_time_format')
            ));

            return parent::initialize();
        }

        /**
         * @access public.
         * @param Object $c.
         * @return Object.
         */
        public function prepareQueryBeforeCount(xPDOQuery $c) {
            $query = $this->getProperty('query');

            if (!empty($query)) {
                $c->where(array(
                    'key:LIKE' 		=> '%'.$query.'%',
                    'OR:name:LIKE' 	=> '%'.$query.'%'
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
            $array = array_merge($object->toArray(), array(
                'name_formatted' 			=> $object->name,
                'description_formatted' 	=> $object->description,
                'data'						=> unserialize($object->data)
            ));

            if (empty($object->icon)) {
                $array['icon'] = 'file';
            }

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

            if (in_array($array['editedon'], array('-001-11-30 00:00:00', '-1-11-30 00:00:00', '0000-00-00 00:00:00', null))) {
                $array['editedon'] = '';
            } else {
                $array['editedon'] = date($this->getProperty('dateFormat'), strtotime($array['editedon']));
            }

            return $array;
        }
    }

    return 'DigitalSignageSlideTypesGetListProcessor';

?>