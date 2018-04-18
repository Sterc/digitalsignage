<?php

    class DigitalSignageBroadcastsFeedsGetListProcessor extends modObjectGetListProcessor {
        /**
         * @access public.
         * @var String.
         */
        public $classKey = 'DigitalSignageBroadcastsFeeds';

        /**
         * @access public.
         * @var Array.
         */
        public $languageTopics = ['digitalsignage:default', 'digitalsignage:slides'];

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
        public $objectType = 'digitalsignage.broadcasts';

        /**
         * @access public.
         * @return Mixed.
         */
        public function initialize() {
            $this->modx->getService('digitalsignage', 'DigitalSignage', $this->modx->getOption('digitalsignage.core_path', null, $this->modx->getOption('core_path') . 'components/digitalsignage/') . 'model/digitalsignage/');

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
                $c->where([
                    'broadcast_id' => $broadcast
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
                'name'          => $object->get('key'),
                'description'   => $object->get('key')
            ]);

            $translationKey = 'digitalsignage.feed_' . str_replace('-', '_', $object->get('key'));

            if ($translationKey !== ($translation = $this->modx->lexicon($translationKey))) {
                $array['name'] = $translation;
            }

            $translationKey = 'digitalsignage.feed_' . str_replace('-', '_', $object->get('key')) . '_desc';

            if ($translationKey !== ($translation = $this->modx->lexicon($translationKey))) {
                $array['description'] = $translation;
            }

            if (in_array($object->get('editedon'), ['-001-11-30 00:00:00', '-1-11-30 00:00:00', '0000-00-00 00:00:00', null])) {
                $array['editedon'] = '';
            } else {
                $array['editedon'] = date($this->getProperty('dateFormat'), strtotime($object->get('editedon')));
            }

            return $array;
        }
    }

    return 'DigitalSignageBroadcastsFeedsGetListProcessor';

?>