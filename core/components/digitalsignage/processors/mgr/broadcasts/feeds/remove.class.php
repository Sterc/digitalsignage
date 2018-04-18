<?php

    class DigitalSignageBroadcastFeedsRemoveProcessor extends modObjectRemoveProcessor {
        /**
         * @access public.
         * @var String.
         */
        public $classKey = 'DigitalSignageBroadcastsFeeds';

        /**
         * @access public.
         * @var Array.
         */
        public $languageTopics = ['digitalsignage:default'];

        /**
         * @access public.
         * @var String.
         */
        public $objectType = 'digitalsignage.broadcasts';

        /**
         * @access public.
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
        public function afterRemove() {
            $c = [
                'id' => $this->object->get('broadcast_id')
            ];

            if (null !== ($broadcast = $this->modx->getObject('DigitalSignageBroadcasts', $c))) {
                $broadcast->fromArray([
                    'hash' => time()
                ]);

                $broadcast->save();
            }

            return parent::afterRemove();
        }
    }

    return 'DigitalSignageBroadcastFeedsRemoveProcessor';

?>