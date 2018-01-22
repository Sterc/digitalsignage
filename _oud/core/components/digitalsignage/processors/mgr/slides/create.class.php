<?php

    class DigitalSignageSlidesCreateProcessor extends modObjectCreateProcessor {
        /**
         * @access public.
         * @var String.
         */
        public $classKey = 'DigitalSignageSlides';

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

            if (null === $this->getProperty('published')) {
                $this->setProperty('published', 0);
            }

            return parent::initialize();
        }

        /**
         * @access public.
         * @return Mixed.
         */
        public function beforeSave() {
            $data = array();

            foreach ($this->getProperties() as $key => $value) {
                if (false !== strstr($key, 'data_')) {
                    $data[substr($key, 5, strlen($key))] = $value;
                }
            }

            $this->object->set('data', serialize($data));

            if (null === ($broadcasts = $this->getProperty('broadcasts'))) {
                $broadcasts = array();
            }

            $currentBroadcastIDs = $this->getBroadcasts(null, true);

            foreach ($broadcasts as $key => $broadcastID) {
                $broadcastIDs = $this->getBroadcasts($broadcastID);

                if (0 < count($broadcastIDs)) {
                    $sortIndex = end(array_keys($broadcastIDs));
                } else {
                    $sortIndex = 0;
                }

                if (0 == count($this->getBroadcasts($broadcastID, true))) {
                    if (null !== ($broadcast = $this->modx->newObject('DigitalSignageBroadcastsSlides'))) {
                        $broadcast->fromArray(array(
                            'broadcast_id'  => $broadcastID,
                            'sortindex'     => $sortIndex + 1
                        ));

                        $this->object->addMany($broadcast);
                    }
                }
            }

            foreach ($currentBroadcastIDs as $broadcast) {
                if (!in_array($broadcast->get('broadcast_id'), $broadcasts)) {
                    $broadcast->remove();
                }
            }

            return parent::beforeSave();
        }

        /**
         * @access protected.
         * @param Int $id.
         * @param Boolean $unique;
         * @return Array.
         */
        protected function getBroadcasts($id = null, $unique = false) {
            $broadcasts = array();

            $c = array();

            if (null !== $id) {
                $c = array_merge($c, array(
                    'broadcast_id' => $id
                ));
            }

            if ($unique) {
                $c = array_merge($c, array(
                    'slide_id' => $this->object->get('id')
                ));
            }

            foreach ($this->modx->getCollection('DigitalSignageBroadcastsSlides', $c) as $broadcast) {
                if (null !== $id) {
                    $broadcasts[$broadcast->get('sortindex')] = $broadcast;
                } else {
                    $broadcasts[] = $broadcast;
                }
            }

            ksort($broadcasts);

            return $broadcasts;
        }
    }

    return 'DigitalSignageSlidesCreateProcessor';

?>