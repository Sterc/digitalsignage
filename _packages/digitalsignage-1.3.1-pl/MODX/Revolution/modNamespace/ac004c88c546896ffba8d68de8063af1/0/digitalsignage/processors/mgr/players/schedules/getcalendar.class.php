<?php

    class DigitalSignagePlayersSchedulesGetCalendarProcessor extends modProcessor {
        /**
         * @access public.
         * @var String.
         */
        public $classKey = 'DigitalSignagePlayersSchedules';

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
         * @return Mixed.
         */
        public function process() {
            $data 	= array();
            $dates 	= array();

            foreach ($this->getSchedules('date') as $schedule) {
                $start = array(
                    'date'	=> $schedule->start_date,
                    'time'	=> $schedule->start_time
                );

                $end = array(
                    'date'	=> $schedule->end_date,
                    'time'	=> $schedule->end_time
                );

                foreach ($schedule->getRange($start, $end) as $date) {
                    $data[] = array(
                        'id'		=> uniqid(),
                        'cid'		=> $schedule->broadcast_id,
                        'title'		=> $schedule->pagetitle,
                        'start'		=> date('Y-m-d\TH:i:sP', strtotime($date['format_start'])),
                        'end'		=> date('Y-m-d\TH:i:sP', strtotime($date['format_end'])),
                        'type'		=> 'date'
                    );
                }
            }

            foreach ($this->getSchedules('day') as $schedule) {
                $start = array(
                    'date'	=> $this->getProperty('start'),
                    'time'	=> $schedule->start_time
                );

                $end = array(
                    'date'	=> $this->getProperty('end'),
                    'time'	=> $schedule->end_time
                );

                foreach ($schedule->getRange($start, $end) as $date) {
                    $data[] = array(
                        'id'		=> uniqid(),
                        'cid'		=> $schedule->broadcast_id,
                        'title'		=> $schedule->pagetitle,
                        'start'		=> date('Y-m-d\TH:i:sP', strtotime($date['format_start'])),
                        'end'		=> date('Y-m-d\TH:i:sP', strtotime($date['format_end'])),
                        'type'		=> 'day'
                    );
                }
            }

            $sort = array();

            foreach ($data as $key => $value) {
                $sort[$key] = $value['type'];
            }

            array_multisort($sort, SORT_DESC, $data);

            return $this->outputArray($data);
        }

        /**
         * @access public.
         * @param String $type.
         * @return Array.
         */
        public function getSchedules($type) {
            $c = $this->modx->newQuery('DigitalSignagePlayersSchedules');

            $c->select($this->modx->getSelectColumns('DigitalSignagePlayersSchedules', 'DigitalSignagePlayersSchedules'));
            $c->select($this->modx->getSelectColumns('DigitalSignageBroadcasts', 'DigitalSignageBroadcasts', null, array('resource_id')));
            $c->select($this->modx->getSelectColumns('modResource', 'modResource', null, array('pagetitle')));

            $c->innerjoin('DigitalSignageBroadcasts', 'DigitalSignageBroadcasts', array('DigitalSignageBroadcasts.id = DigitalSignagePlayersSchedules.broadcast_id'));
            $c->innerjoin('modResource', 'modResource', array('modResource.id = DigitalSignageBroadcasts.resource_id'));

            $c->where(array(
                'DigitalSignagePlayersSchedules.player_id' 	=> $this->getProperty('player_id'),
                'DigitalSignagePlayersSchedules.type'		=> $type
            ));

            return $this->modx->getCollection('DigitalSignagePlayersSchedules', $c);
        }
    }

    return 'DigitalSignagePlayersSchedulesGetCalendarProcessor';

?>