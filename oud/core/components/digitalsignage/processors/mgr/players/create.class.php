<?php

    class DigitalSignagePlayersCreateProcessor extends modObjectCreateProcessor {
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

            if (null === ($key = $this->getProperty('key'))) {
                $unique = false;

                while (!$unique) {
                    $criterea = array(
                        'key' => $this->generatePlayerKey()
                    );

                    if (null === $this->modx->getObject('DigitalSignagePlayers', $criterea)) {
                        $this->setProperty('key', $criterea['key']);

                        $unique = true;
                    }
                }
            }

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

        /**
         * @access public.
         * @return String.
         */
        public function generatePlayerKey() {
            $key = '';
            $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

            for ($i = 0; $i < 12; $i++) {
                $key .= $characters[rand(0, strlen($characters) - 1)];
            }

            return implode(':', str_split($key, 3));
        }
    }

    return 'DigitalSignagePlayersCreateProcessor';

?>