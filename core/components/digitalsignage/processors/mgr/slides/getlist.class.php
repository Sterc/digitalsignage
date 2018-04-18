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
        public $languageTopics = ['digitalsignage:default'];

        /**
         * @access public.
         * @var String.
         */
        public $defaultSortField = 'DigitalSignageSlides.id';

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
         * @return Mixed.
         */
        public function initialize() {
            $this->modx->getService('digitalsignage', 'DigitalSignage', $this->modx->getOption('digitalsignage.core_path', null, $this->modx->getOption('core_path') . 'components/digitalsignage/') . 'model/digitalsignage/');

            $this->setDefaultProperties([
                'dateFormat' => $this->modx->getOption('manager_date_format') . ', ' . $this->modx->getOption('manager_time_format')
            ]);

            return parent::initialize();
        }

        /**
        * @access public.
        * @param Object $c.
        * @return Object.
        */
        public function prepareQueryBeforeCount(xPDOQuery $c) {
            $broadcast = $this->getProperty('broadcast_id');

            if (!empty($broadcast)) {
                $c->innerJoin('DigitalSignageBroadcastsSlides', 'DigitalSignageBroadcastsSlides', [
                    'DigitalSignageBroadcastsSlides.slide_id = DigitalSignageSlides.id'
                ]);

                $c->where([
                    'DigitalSignageBroadcastsSlides.broadcast_id' => $broadcast
                ]);
            }

            $query = $this->getProperty('query');

            if (!empty($query)) {
                $c->where([
                    'DigitalSignageSlides.name:LIKE' => '%' . $query . '%'
                ]);
            }

            return $c;
        }

        /**
         * @access public.
         * @param Object $object.
         * @return Array.
         */
        public function prepareRow(xPDOObject $object) {
            $array = array_merge($object->toArray(), [
                'data'              => unserialize($object->get('data')),
                'icon'              => 'file',
                'type_formatted'    => $object->get('type'),
                'broadcasts'        => []
            ]);

            if (null !== ($type = $object->getOne('getSlideType'))) {
                if (!empty($type->get('icon'))) {
                    $array['icon'] = $type->get('icon');
                }

                $translationKey = 'digitalsignage.slide_' . $type->get('key');

                if ($translationKey !== ($translation = $this->modx->lexicon($translationKey))) {
                    $array['type_formatted'] = $translation;
                } else {
                    $array['type_formatted'] = $type->get('name');
                }
            }
            foreach ($object->getBroadcasts() as $key => $broadcast) {
                $array['broadcasts'][] = $broadcast->get('id');
            }

            if (in_array($object->get('editedon'), ['-001-11-30 00:00:00', '-1-11-30 00:00:00', '0000-00-00 00:00:00', null])) {
                $array['editedon'] = '';
            } else {
                $array['editedon'] = date($this->getProperty('dateFormat'), strtotime($object->get('editedon')));
            }

            return $array;
        }
    }

    return 'DigitalSignageSlidesGetListProcessor';

?>