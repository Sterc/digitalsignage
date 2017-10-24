<?php

    class DigitalSignageBroadcastsRemoveProcessor extends modObjectRemoveProcessor {
        /**
         * @acces public.
         * @var String.
         */
        public $classKey = 'DigitalSignageBroadcasts';

        /**
         * @acces public.
         * @var Array.
         */
        public $languageTopics = array('digitalsignage:default');

        /**
         * @acces public.
         * @var String.
         */
        public $objectType = 'digitalsignage.broadcasts';

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
        public function beforeRemove() {
            $response = $this->modx->runProcessor('resource/delete', array(
                'id' => $this->object->resource_id
            ));

            return parent::beforeRemove();
        }

        /**
         * @acces public.
         * @return Mixed.
         */
        public function afterRemove() {
            foreach ($this->object->getMany('getSlides') as $slide) {
                $slide->remove();
            }

            foreach ($this->object->getMany('getFeeds') as $feed) {
                $feed->remove();
            }

            foreach ($this->object->getMany('getSchedules') as $schedulde) {
                $schedulde->remove();
            }

            return parent::afterRemove();
        }
    }

    return 'DigitalSignageBroadcastsRemoveProcessor';

?>