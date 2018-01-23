<?php

    class DigitalSignageBroadcastsGetNodesProcessor extends modObjectGetListProcessor {
        /**
         * @access public.
         * @var String.
         */
        public $classKey = 'DigitalSignageBroadcasts';

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

            $this->setDefaultProperties(array(
                'dateFormat' 	=> $this->modx->getOption('manager_date_format') .', '. $this->modx->getOption('manager_time_format')
            ));

            return parent::initialize();
        }

        /**
         * @access public.
         * @param Object $c.
         * @return Object.
         */
        public function prepareQueryBeforeCount(xPDOQuery $c) {
            $c->select($this->modx->getSelectColumns('DigitalSignageBroadcasts', 'DigitalSignageBroadcasts'));
            $c->select($this->modx->getSelectColumns('modResource', 'modResource', null, array('pagetitle')));

            $c->innerjoin('modResource', 'modResource', array('modResource.id = DigitalSignageBroadcasts.resource_id'));

            return $c;
        }

        /**
         * @access public.
         * @param Object $query.
         * @return Array.
         */
        public function prepareRow(xPDOObject $object) {
            $array = array_merge($object->toArray(), array(
                'name'				=> $object->pagetitle,
                'calendar'			=> $object->color
            ));

            return $array;
        }
    }

    return 'DigitalSignageBroadcastsGetNodesProcessor';

?>