<?php

    class DigitalSignageSlidesUpdateProcessor extends modObjectUpdateProcessor {
        /**
         * @access public.
         * @var String.
         */
        public $classKey = 'DigitalSignageSlides';

        /**
         * @access public.
         * @var Array.
         */
        public $languageTopics = ['digitalsignage:default'];

        /**
         * @access public.
         * @var String.
         */
        public $objectType = 'digitalsignage.slides';

        /**
         * @access public.
         * @return Mixed.
         */
        public function initialize() {
            $this->modx->getService('digitalsignage', 'DigitalSignage', $this->modx->getOption('digitalsignage.core_path', null, $this->modx->getOption('core_path') . 'components/digitalsignage/') . 'model/digitalsignage/');

            if ($this->getProperty('published') === null) {
                $this->setProperty('published', 0);
            }

            if ($this->modx->hasPermission('digitalsignage_settings')) {
                if ($this->getProperty('protected', null) === null) {
                    $this->setProperty('protected', 0);
                } else {
                    $this->setProperty('protected', 1);
                }
            } else {
                $this->unsetProperty('protected');
            }

            return parent::initialize();
        }

        /**
         * @access public.
         * @return Mixed.
         */
        public function beforeSave() {
            $data = [];

            foreach ($this->getProperties() as $key => $value) {
                if (false !== strpos($key, 'data_')) {
                    if ($key == 'data_selected' && !empty($data['url'])) {
                        $results = json_decode(file_get_contents($data['url']), true);
                        $unchecked = [];
                        foreach ($results['items'] as $item) {
                            if (!in_array($item['id'], $value)) {
                                $unchecked[] = $item['id'];
                            }
                        }
                        $value = $unchecked;
                    }
                    $data[substr($key, 5, strlen($key))] = $value;
                }
            }

            $this->object->set('data', serialize($data));

            if (null === ($broadcasts = $this->getProperty('broadcasts'))) {
                $broadcasts = [];
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
                        $broadcast->fromArray([
                            'broadcast_id'  => $broadcastID,
                            'sortindex'     => $sortIndex + 1
                        ]);

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
         * @param Integer $id.
         * @param Boolean $unique;
         * @return Array.
         */
        protected function getBroadcasts($id = null, $unique = false) {
            $broadcasts = [];

            $c = [];

            if (null !== $id) {
                $c = array_merge($c, [
                    'broadcast_id' => $id
                ]);
            }

            if ($unique) {
                $c = array_merge($c, [
                    'slide_id' => $this->object->get('id')
                ]);
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

    return 'DigitalSignageSlidesUpdateProcessor';

?>