<?php

    class DigitalSignagePlayersSchedulesGetListProcessor extends modObjectGetListProcessor {
        /**
         * @access public.
         * @var String.
         */
        public $classKey = 'DigitalSignagePlayersSchedules';

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
        public $objectType = 'digitalsignage.players';

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
            $c->select($this->modx->getSelectColumns('DigitalSignagePlayersSchedules', 'DigitalSignagePlayersSchedules'));
            $c->select($this->modx->getSelectColumns('DigitalSignageBroadcasts', 'DigitalSignageBroadcasts', null, array('resource_id', 'color')));
            $c->select($this->modx->getSelectColumns('modResource', 'modResource', null, array('pagetitle', 'template')));

            $c->innerjoin('DigitalSignageBroadcasts', 'DigitalSignageBroadcasts', array('DigitalSignageBroadcasts.id = DigitalSignagePlayersSchedules.broadcast_id'));
            $c->innerjoin('modResource', 'modResource', array('modResource.id = DigitalSignageBroadcasts.resource_id'));

            $player = $this->getProperty('player_id');

            if (!empty($player)) {
                $c->where(array(
                    'DigitalSignagePlayersSchedules.player_id' => $player
                ));
            }

            $broadcast = $this->getProperty('broadcast_id');

            if (!empty($broadcast)) {
                $c->where(array(
                    'DigitalSignagePlayersSchedules.broadcast_id' => $broadcast
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
            return array_merge($object->toArray(), array(
                'broadcast'				=> array(
                    'name'					=> $object->pagetitle,
                    'color'					=> $object->color
                ),
                'start_time' 			=> date($this->modx->getOption('manager_time_format'), strtotime($object->start_time)),
                'start_date' 			=> date($this->modx->getOption('manager_date_format'), strtotime($object->start_date)),
                'end_time' 				=> date($this->modx->getOption('manager_time_format'), strtotime($object->end_time)),
                'end_date' 				=> date($this->modx->getOption('manager_date_format'), strtotime($object->end_date)),
                'type_formatted' 		=> $this->modx->lexicon('digitalsignage.schedule_'.$object->type),
                'date_formatted' 		=> $object->toString(),
                'entire_day'			=> $object->isEntireDay()
            ));
        }
    }

    return 'DigitalSignagePlayersSchedulesGetListProcessor';

?>