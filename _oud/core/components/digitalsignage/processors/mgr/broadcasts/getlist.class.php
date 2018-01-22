<?php

    class DigitalSignageBroadcastsGetListProcessor extends modObjectGetListProcessor {
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
            $c->select($this->modx->getSelectColumns('DigitalSignageBroadcasts', 'DigitalSignageBroadcasts'));
            $c->select($this->modx->getSelectColumns('modResource', 'modResource', null, array('pagetitle', 'description', 'template')));

            $c->innerjoin('modResource', 'modResource', array('modResource.id = DigitalSignageBroadcasts.resource_id'));

            $query = $this->getProperty('query');

            if (!empty($query)) {
                $c->where(array(
                    'modResource.pagetitle:LIKE' => '%'.$query.'%'
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
                'name'				=> $object->pagetitle,
                'name_formatted'	=> $object->pagetitle.($this->modx->hasPermission('tree_show_resource_ids') ? ' ('.$object->resource_id.')' : ''),
                'slides'			=> count($object->getSlides()),
                'feeds'				=> count($object->getFeeds()),
                'players' 			=> array(),
                'sync'				=> array(
                    'valid'				=> !$object->needSync(),
                    'timestamp'			=> $object->getLastSync()
                )
            ));

            foreach ($object->getPlayers() as $player) {
                $array['players'][] = array(
                    'key'		=> $player->key,
                    'name'		=> $player->name,
                    'online' 	=> $player->isOnline($object->id)
                );
            }

            if (in_array($array['sync']['timestamp'], array('-001-11-30 00:00:00', '-1-11-30 00:00:00', '0000-00-00 00:00:00', null))) {
                $array['sync']['timestamp'] = '';
            } else {
                $array['sync']['timestamp'] = date($this->getProperty('dateFormat'), strtotime($array['sync']['timestamp']));
            }

            if (in_array($array['editedon'], array('-001-11-30 00:00:00', '-1-11-30 00:00:00', '0000-00-00 00:00:00', null))) {
                $array['editedon'] = '';
            } else {
                $array['editedon'] = date($this->getProperty('dateFormat'), strtotime($array['editedon']));
            }

            return $array;
        }
    }

    return 'DigitalSignageBroadcastsGetListProcessor';

?>