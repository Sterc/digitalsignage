<?php

    class DigitalSignagePlayersUpdateProcessor extends modObjectUpdateProcessor {
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
        public $objectType = 'digitalsignage.players';

        /**
         * @acces public.
         * @return Mixed.
         */
        public function initialize() {
            $this->modx->getService('digitalsignage', 'DigitalSignage', $this->modx->getOption('digitalsignage.core_path', null, $this->modx->getOption('core_path') . 'components/digitalsignage/') . 'model/digitalsignage/');

            return parent::initialize();
        }

        /**
         * @access public.
         * @return Mixed.
         */
        public function beforeSave() {
            if (!preg_match('/^(\d+)x(\d+)$/', $this->getProperty('resolution'))) {
                $this->addFieldError('resolution', $this->modx->lexicon('digitalsignage.error_player_resolution'));
            }

            return parent::beforeSave();
        }
    }

    return 'DigitalSignagePlayersUpdateProcessor';

?>