<?php

    class DigitalSignagePlayersRemoveProcessor extends modObjectRemoveProcessor {
        /**
         * @acces public.
         * @var String.
         */
        public $classKey = 'DigitalSignagePlayers';

        /**
         * @acces public.
         * @var Array.
         */
        public $languageTopics = ['digitalsignage:default'];

        /**
         * @acces public.
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
         * @acces public.
         * @return Mixed.
         */
        public function afterRemove() {
            foreach ($this->object->getMany('getSchedules') as $schedule) {
                $schedule->remove();
            }

            return parent::afterRemove();
        }
    }

    return 'DigitalSignagePlayersRemoveProcessor';

?>