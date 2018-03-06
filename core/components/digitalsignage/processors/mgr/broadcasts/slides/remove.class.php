<?php

    class DigitalSignageSlidesRemoveProcessor extends modObjectRemoveProcessor {
        /**
         * @acces public.
         * @var String.
         */
        public $classKey = 'DigitalSignageBroadcastsSlides';

        /**
         * @acces public.
         * @var Array.
         */
        public $languageTopics = ['digitalsignage:default'];

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
            $this->digitalsignage = $this->modx->getService('digitalsignage', 'DigitalSignage', $this->modx->getOption('digitalsignage.core_path', null, $this->modx->getOption('core_path') . 'components/digitalsignage/') . 'model/digitalsignage/');

            return parent::initialize();
        }

        /**
         * @acces public.
         * @return Mixed.
         */
        public function afterRemove() {
            if (null !== ($broadcast = $this->object->getOne('getBroadcast'))) {
                $broadcast->fromArray(array(
                    'hash' => time()
                ));

                $broadcast->save();
            }

            return parent::afterRemove();
        }
    }

    return 'DigitalSignageSlidesRemoveProcessor';

?>