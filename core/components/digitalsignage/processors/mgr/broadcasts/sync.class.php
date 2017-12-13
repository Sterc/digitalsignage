<?php

    class DigitalSignageBroadcastsSynchronizeProcessor extends modObjectUpdateProcessor {
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
            if (null !== ($resource = $this->object->getResource())) {
                $slides = array();

                foreach ($this->object->getSlides() as $key => $slide) {
                    $data = unserialize($slide->data);

                    if (!is_array($data)) {
                        $data = array();
                    }

                    $slides[] = array_merge(array(
                        'id'        => $slide->get('id'),
                        'time'      => $slide->get('time'),
                        'slide'     => $slide->get('type'),
                        'source'    => 'intern',
                        'title'     => $slide->get('name'),
                        'image'     => null
                    ), $data);
                }

                if (!$this->object->toExport($slides)) {
                    return $this->failure($this->modx->lexicon('digitalsignage.error_broadcast_sync'));
                }
            }

            return $this->outputArray(array());
        }
    }

    return 'DigitalSignageBroadcastsSynchronizeProcessor';

?>