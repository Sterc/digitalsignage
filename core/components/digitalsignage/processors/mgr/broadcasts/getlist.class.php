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
        public $languageTopics = ['digitalsignage:default'];

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
            $this->digitalsignage = $this->modx->getService('digitalsignage', 'DigitalSignage', $this->modx->getOption('digitalsignage.core_path', null, $this->modx->getOption('core_path') . 'components/digitalsignage/') . 'model/digitalsignage/');

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
            $c->select($this->modx->getSelectColumns('DigitalSignageBroadcasts', 'DigitalSignageBroadcasts'));
            $c->select($this->modx->getSelectColumns('modResource', 'modResource', null, ['pagetitle', 'description', 'template']));

            $c->innerjoin('modResource', 'modResource', ['modResource.id = DigitalSignageBroadcasts.resource_id']);

            $query = $this->getProperty('query');

            if (!empty($query)) {
                $c->where([
                    'modResource.pagetitle:LIKE' => '%' . $query . '%'
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
                'name'              => $object->get('pagetitle'),
                'name_formatted'    => $object->get('pagetitle') . ($this->modx->hasPermission('tree_show_resource_ids') ? ' (' . $object->get('resource_id') . ')' : ''),
                'slides'            => count($object->getSlides()),
                'feeds'             => count($object->getFeeds()),
                'players'           => [],
                'sync'              => [
                    'valid'             => !$object->needSync(),
                    'timestamp'         => $object->getLastSync()
                ]
            ]);

            foreach ($object->getPlayers() as $player) {
                $array['players'][] = [
                    'key'       => $player->get('key'),
                    'name'      => $player->get('name'),
                    'online'        => $player->isOnline($object->get('id'))
                ];
            }

            if (in_array($array['sync']['timestamp'], ['-001-11-30 00:00:00', '-1-11-30 00:00:00', '0000-00-00 00:00:00', null])) {
                $array['sync']['timestamp'] = '';
            } else {
                $array['sync']['timestamp'] = date($this->getProperty('dateFormat'), strtotime($array['sync']['timestamp']));
            }

            if (in_array($array['editedon'], ['-001-11-30 00:00:00', '-1-11-30 00:00:00', '0000-00-00 00:00:00', null])) {
                $array['editedon'] = '';
            } else {
                $array['editedon'] = date($this->getProperty('dateFormat'), strtotime($array['editedon']));
            }

            return $array;
        }
    }

    return 'DigitalSignageBroadcastsGetListProcessor';

?>