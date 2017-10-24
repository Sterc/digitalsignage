<?php

    class DigitalSignageSlidesGetListProcessor extends modObjectGetListProcessor {
        /**
         * @access public.
         * @var String.
         */
        public $classKey = 'DigitalSignageSlides';

        /**
         * @access public.
         * @var Array.
         */
        public $languageTopics = array('digitalsignage:default');

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
        public $objectType = 'digitalsignage.slides';

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
                    'name:LIKE' => '%'.$query.'%'
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
                'data'				=> unserialize($object->data),
                'type_formatted' 	=> $object->type,
                'broadcasts'        => array()
            ));

            if (null !== ($type = $object->getOne('getSlideType'))) {
                $array['type_formatted'] = $type->name;

                $translationKey = 'digitalsignage.slide_'.$type->key;

                if ($translationKey !== ($translation = $this->modx->lexicon($translationKey))) {
                    $array['type_formatted'] = $translation;
                }
            }

            foreach ($object->getBroadcasts() as $key => $broadcast) {
                $array['broadcasts'][] = $broadcast->get('id');
            }

            if (in_array($array['editedon'], array('-001-11-30 00:00:00', '-1-11-30 00:00:00', '0000-00-00 00:00:00', null))) {
                $array['editedon'] = '';
            } else {
                $array['editedon'] = date($this->getProperty('dateFormat'), strtotime($array['editedon']));
            }

            return $array;
        }
    }

    return 'DigitalSignageSlidesGetListProcessor';

?>