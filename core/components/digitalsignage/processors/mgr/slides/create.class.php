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

            /* publish and unpublish dates */
            $now = time();
            if (empty($this->getProperty('pub_date'))) {
                $this->setProperty('pub_date', 0);
            } else {
                $pub_date = strtotime($this->getProperty('pub_date'));
                if ($pub_date <= $now) {
                    $this->setProperty('published', 1);
                    $this->setProperty('pub_date', 0);
                }
                if ($pub_date  > $now) $this->setProperty('published', 0);
            }
            if (empty($this->getProperty('unpub_date'))) {
                $this->setProperty('unpub_date', 0);
            } else {
                $unpub_date = strtotime($this->getProperty('unpub_date'));
                if ($unpub_date < $now) {
                    $this->setProperty('published', 0);
                    $this->setProperty('unpub_date', 0);
                }
            }
            if (!empty($this->getProperty('pub_date')) || !empty($this->getProperty('unpub_date'))) {
                $cache_key     = 'auto_publish';
                $cache_options = [xPDO::OPT_CACHE_KEY => 'digitalsignage'];
                $nextevent     = 0;
                $this->modx->cacheManager->set($cache_key, $nextevent, 0, $cache_options);
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

                if (0 === count($this->getBroadcasts($broadcastID, true))) {
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

    return 'DigitalSignageSlidesCreateProcessor';

?>