<?php

    class DigitalSignageSlidesRemoveProcessor extends modObjectRemoveProcessor {
        /**
         * @acces public.
         * @var String.
         */
        public $classKey = 'DigitalSignageSlides';

        /**
         * @acces public.
         * @var Array.
         */
        public $languageTopics = array('digitalsignage:default');

        /**
         * @acces public.
         * @var String.
         */
        public $objectType = 'digitalsignage.slides';

        /**
         * @acces public.
         * @var Object.
         */
        public $digitalsignage;

        /**
         * @acces public.
         * @return Mixed.
         */
        public function initialize() {
            $this->digitalsignage = $this->modx->getService('digitalsignage', 'DigitalSignage', $this->modx->getOption('digitalsignage.core_path', null, $this->modx->getOption('core_path').'components/digitalsignage/').'model/digitalsignage/');

            return parent::initialize();
        }

        /**
         * @acces public.
         * @return Mixed.
         */
        public function afterRemove() {
            foreach ($this->object->getMany('getBroadcasts') as $broadcast) {
                $broadcast->remove();
            }

            return parent::afterRemove();
        }
    }

    return 'DigitalSignageSlidesRemoveProcessor';

?>