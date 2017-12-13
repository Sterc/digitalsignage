<?php

    class DigitalSignagePlayersGetNodesProcessor extends modObjectGetListProcessor {
        /**
         * @access public.
         * @var String.
         */
        public $classKey = 'DigitalSignagePlayers';

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
            $query = $this->getProperty('query');

            if (!empty($query)) {
                $c->where(array(
                    'key:LIKE' 			=> '%'.$query.'%',
                    'OR:name:LIKE' 		=> '%'.$query.'%'
                ));
            }

            return $c;
        }
    }

    return 'DigitalSignagePlayersGetNodesProcessor';

?>