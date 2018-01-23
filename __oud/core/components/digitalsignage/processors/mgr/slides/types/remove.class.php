<?php

    class DigitalSignageSlideTypesRemoveProcessor extends modObjectRemoveProcessor {
        /**
         * @acces public.
         * @var String.
         */
        public $classKey = 'DigitalSignageSlidesTypes';

        /**
         * @acces public.
         * @var Array.
         */
        public $languageTopics = array('digitalsignage:default');

        /**
         * @access public.
         * @var String.
         */
        public $primaryKeyField = 'key';

        /**
         * @acces public.
         * @var String.
         */
        public $objectType = 'digitalsignage.slidestypes';

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
    }

    return 'DigitalSignageSlideTypesRemoveProcessor';

?>