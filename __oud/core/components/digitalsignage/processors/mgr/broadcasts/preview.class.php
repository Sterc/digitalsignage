<?php

    class DigitalSignageBroadcastsUpdateProcessor extends modObjectGetProcessor {
        /**
         * @access public.
         * @var String.
         */
        public $classKey = 'DigitalSignageBroadcasts';

        /**
         * @access public.
         * @var Array.
         */
        public $languageTopics = array('digitalsignage:default');

        /**
         * @access public.
         * @var String.
         */
        public $objectType = 'digitalsignage.broadcasts';

        /**
         * @access public.
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
        public function process() {
            if (null !== ($resource = $this->object->getResource())) {
                $criterea = array(
                    'id' => $this->getProperty('player')
                );

                if (null !== ($player = $this->modx->getObject('DigitalSignagePlayers', $criterea))) {
                    list($width, $height) = explode('x', $player->resolution);

                    return $this->success(null, array_merge($player->toArray(), array(
                        'url' 	=> $this->modx->makeUrl($resource->id, $resource->context_key, array(
                            'pl'		=> $player->key,
                            'bc'		=> $this->object->id,
                            'preview' 	=> true
                        ), 'full'),
                        'width'		=> $width,
                        'height'	=> $height,
                    )));
                }
            }

            return $this->failure();
        }
    }

    return 'DigitalSignageBroadcastsUpdateProcessor';

?>