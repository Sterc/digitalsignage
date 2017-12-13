<?php

    class DigitalSignageBroadcastsSynchronizeSelectedProcessor extends modProcessor {
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
         * @access public.
         * @return Mixed.
         */
        public function initialize() {
            $this->digitalsignage = $this->modx->getService('digitalsignage', 'DigitalSignage', $this->modx->getOption('digitalsignage.core_path', null, $this->modx->getOption('core_path').'components/digitalsignage/').'model/digitalsignage/');

            return parent::initialize();
        }

        /**
         * @acccess public.
         * @return Mixed.
         */
        public function process() {
            foreach (explode(',' , $this->getProperty('ids')) as $id) {
                $c = array(
                    'id' => $id
                );

                if (null !== ($object = $this->modx->getObject($this->classKey, $c))) {
;                    if (null !== ($resource = $object->getResource())) {
                        $slides = array();

                        foreach ($object->getSlides() as $key => $slide) {
                            $data = unserialize($slide->data);

                            if (!is_array($data)) {
                                $data = array();
                            }

                            $slides[] = array_merge(array(
                                'time'  	=> $slide->time,
                                'slide' 	=> $slide->type,
                                'source'	=> 'intern',
                                'title' 	=> $slide->name,
                                'image' 	=> null
                            ), $data);
                        }

                        if (!$object->toExport($slides)) {
                            return $this->failure($this->modx->lexicon('digitalsignage.error_broadcast_sync'));
                        }
                    }
                }
            }

            return $this->outputArray(array());
        }
    }

    return 'DigitalSignageBroadcastsSynchronizeSelectedProcessor';

?>