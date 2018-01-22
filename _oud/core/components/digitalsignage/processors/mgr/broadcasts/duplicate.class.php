<?php

    class DigitalSignageBroadcastsDuplicateProcessor extends modObjectDuplicateProcessor {
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
         * @acces public.
         * @return Mixed.
         */
        public function beforeSave() {
            $this->newObject->set('has', time());
            $this->newObject->set('color', rand(1, 32));

            $response = $this->modx->runProcessor('resource/duplicate', array(
                'id' 	        => $this->object->get('resource_id'),
                'name'	        => $this->newObject->get('name')
            ));

            if ($response->isError()) {
                foreach ($response->getFieldErrors() as $error) {
                    $this->addFieldError('name', $error->message);
                }
            }

            if (null !== ($object = $response->getObject())) {
                if (isset($object['id'])) {
                    $this->newObject->set('resource_id', $object['id']);
                } else {
                    $this->addFieldError('name', $this->modx->lexicon('error_resource_object'));
                }
            } else {
                $this->addFieldError('name', $this->modx->lexicon('error_resource_object'));
            }

            if (!preg_match('/^(http|https)/si', $this->getProperty('ticker_url'))) {
                $this->setProperty('url', 'http://'.$this->getProperty('ticker_url'));
            }

            foreach ($this->object->getMany('getSlides') as $slide) {
                if (null !== ($object = $this->modx->newObject('DigitalSignageBroadcastsSlides'))) {
                    $object->fromArray($slide->toArray());

                    $this->newObject->addMany($object);
                }
            }

            foreach ($this->object->getMany('getFeeds') as $feed) {
                if (null !== ($object = $this->modx->newObject('DigitalSignageBroadcastsFeeds'))) {
                    $object->fromArray($feed->toArray());

                    $this->newObject->addMany($object);
                }
            }

            return parent::beforeSave();
        }

        /**
         * @access public.
         * @return Mixed.
         */
        public function afterSave() {
            $this->modx->cacheManager->refresh(array(
                'db'                => array(),
                'auto_publish'      => array(
                    'contexts'          => array($this->modx->getOption('digitalsignage.context'))
                ),
                'context_settings'  => array(
                    'contexts'          => array($this->modx->getOption('digitalsignage.context'))
                ),
                'resource'          => array(
                    'contexts'          => array($this->modx->getOption('digitalsignage.context'))
                )
            ));

            $this->modx->call('modResource', 'refreshURIs', array(&$this->modx));

            return parent::afterSave();
        }
    }

    return 'DigitalSignageBroadcastsDuplicateProcessor';

?>