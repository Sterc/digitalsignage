<?php

    class DigitalSignagePlayersGetListProcessor extends modObjectGetListProcessor {
        /**
         * @access public.
         * @var String.
         */
        public $classKey = 'DigitalSignagePlayers';

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
        public $objectType = 'digitalsignage.players';

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
            $query = $this->getProperty('query');

            if (!empty($query)) {
                $c->where([
                    'key:LIKE'      => '%' . $query . '%',
                    'OR:name:LIKE'  => '%' . $query . '%'
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
                'mode'              => $object->getMode(),
                'mode_formatted'    => $this->modx->lexicon('digitalsignage.' . $this->modx->lexicon($object->getMode())),
                'online'            => $object->isOnline(),
                'current_broadcast' => '',
                'next_sync'         => '',
                'url'               => $this->modx->digitalsignage->config['request_url']
            ]);

            if (false === strpos($array['url'], '?')) {
                $array['url'] = $array['url'] . '?' . $this->modx->digitalsignage->config['request_param_player'] . '=' . $object->get('key');
            } else {
                $array['url'] = $array['url'] . '&' . $this->modx->digitalsignage->config['request_param_player'] . '=' . $object->get('key');
            }

            if ($object->isOnline()) {
                if (null !== ($broadcast = $object->getCurrentBroadcast())) {
                    if ($resource = $broadcast->getResource()) {
                        $array['current_broadcast'] = $resource->get('pagetitle');
                    }
                }

                $array['next_sync'] = (strtotime($object->get('last_online')) + $object->get('last_online_time')) - time();
            } else {
                $array['restart'] = 0;
            }

            if (in_array($object->get('editedon'), ['-001-11-30 00:00:00', '-1-11-30 00:00:00', '0000-00-00 00:00:00', null])) {
                $array['editedon'] = '';
            } else {
                $array['editedon'] = date($this->getProperty('dateFormat'), strtotime($object->get('editedon')));
            }

            return $array;
        }
    }

    return 'DigitalSignagePlayersGetListProcessor';

?>