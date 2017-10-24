<?php

    class DigitalSignageSlidesSortProcessor extends modProcessor {
        /**
         * @access public.
         * @var String.
         */
        public $classKey = 'DigitalSignageBroadcastsSlides';

        /**
         * @access public.
         * @var Array.
         */
        public $languageTopics = array('digitalsignage:default');

        /**
         * @access public.
         * @var String.
         */
        public $objectType = 'digitalsignage.slides';

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
            $data = urldecode($this->getProperty('data', ''));

            if (empty($data)) {
                $this->failure($this->modx->lexicon('invalid_data'));
            }

            $data = $this->modx->fromJSON($data);

            if (empty($data)) {
                $this->failure($this->modx->lexicon('invalid_data'));
            }

            $c = array(
                'id' => $this->getProperty('source_pk')
            );

            if (null !== ($broadcast = $this->modx->getObject('DigitalSignageBroadcasts', $c))) {
                $index = 1;
                $nodes = array();

                foreach ($data as $key => $children) {
                    $node = $this->getNodeID($key);

                    if (empty($children)) {
                        if (isset($node['id'])) {
                            $c = array(
                                'id' => $node['id']
                            );

                            if (null !== ($object = $this->modx->getObject('DigitalSignageBroadcastsSlides', $c))) {
                                $object->fromArray(array(
                                    'broadcast_id'	=> $broadcast->id,
                                    'slide_id'		=> $node['slide'],
                                    'sortindex' 	=> $index
                                ));

                                if ($object->save()) {
                                    $nodes[$key] = array(
                                        'id'		=> 'n_slide:'.$object->slide_id.'_id:'.$object->id,
                                        'data'		=> $object->toArray()
                                    );
                                }


                            }
                        } else {
                            if (null !== ($object = $this->modx->newObject('DigitalSignageBroadcastsSlides'))) {
                                $object->fromArray(array(
                                    'broadcast_id'	=> $broadcast->id,
                                    'slide_id'		=> $node['slide'],
                                    'sortindex'		=> $index
                                ));

                                if ($object->save()) {
                                    $nodes[$key] = array(
                                        'id'		=> 'n_slide:'.$object->slide_id.'_id:'.$object->id,
                                        'data'		=> $object->toArray()
                                    );
                                }
                            }
                        }

                        $index++;
                    }
                }

                $broadcast->fromArray(array(
                    'hash' => time()
                ));

                $broadcast->save();
            }

            return $this->outputArray($nodes);
        }

        /**
         * @access public.
         * @param String $node.
         * @return Array.
         */
        public function getNodeID($node) {
            $data = array();

            foreach (explode('_', str_replace('n_', '', $node)) as $part) {
                list($type, $value) = explode(':', $part, 2);

                $data[$type] = $value;
            }

            return $data;
        }
    }

    return 'DigitalSignageSlidesSortProcessor';

?>