<?php

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
                'name_formatted'            => $object->name,
                'description_formatted'     => $object->description,
                'data'                      => array()
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

            $sort   = array();
            $data   = unserialize($object->data);

            if (!is_array($data)) {
                $data = array();
            }

            foreach ($data as $key => $row) {
                if (isset($row['menuindex'])) {
                    $sort[$key] = $row['menuindex'];
                } else {
                    $sort[$key] = $row['key'];
                }
            }

            array_multisort($sort, SORT_ASC, $data);

            $array['data'] = $data;

            return $array;
        }
    }

    return 'DigitalSignageSlidesTypesGetListProcessor';

?>