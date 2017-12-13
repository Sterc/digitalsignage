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
        public $languageTopics = array('digitalsignage:default', 'digitalsignage:slides');

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
         * @param Object $c.
         * @return Object.
         */
        public function prepareQueryBeforeCount(xPDOQuery $c) {
            $broadcast = $this->getProperty('broadcast_id');

            if (!empty($broadcast)) {
                $c->where(array(
                    'broadcast_id' => $broadcast
                ));
            }

            return $c;
        }

        /**
         * @access public.
         * @param Object $object.
         * @return Array.
         */
        public function prepareRow(xPDOObject $object) {
            $array = array_merge($object->toArray(), array(
                'name'			=> $this->modx->lexicon('digitalsignage.feed_'.str_replace('-', '_', $object->key)),
                'description'	=> $this->modx->lexicon('digitalsignage.feed_'.str_replace('-', '_', $object->key).'_desc')
            ));

            if (in_array($array['editedon'], array('-001-11-30 00:00:00', '-1-11-30 00:00:00', '0000-00-00 00:00:00', null))) {
                $array['editedon'] = '';
            } else {
                $array['editedon'] = date($this->getProperty('dateFormat'), strtotime($array['editedon']));
            }

            return $array;
        }
    }

    return 'DigitalSignageBroadcastsFeedsGetListProcessor';

?>