<?php

    class DigitalSignageBroadcastsCreateProcessor extends modObjectCreateProcessor {
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
            $this->object->set('has', time());
            $this->object->set('color', rand(1, 32));

            $response = $this->modx->runProcessor('resource/create', array(
                'pagetitle' 	=> $this->getProperty('name'),
                'description'	=> $this->getProperty('description'),
                'alias'			=> $this->getProperty('name'),
                'context_key'	=> $this->modx->getOption('digitalsignage.context'),
                'template'		=> $this->getProperty('template'),
                'show_in_tree'	=> 0
            ));

            if ($response->isError()) {
                foreach ($response->getFieldErrors() as $error) {
                    $this->addFieldError('name', $error->message);
                }
            }

            if (null !== ($object = $response->getObject())) {
                if (isset($object['id'])) {
                    $this->object->set('resource_id', $object['id']);
                } else {
                    $this->addFieldError('name', $this->modx->lexicon('error_resource_object'));
                }
            } else {
                $this->addFieldError('name', $this->modx->lexicon('error_resource_object'));
            }

            if (!preg_match('/^(http|https)/si', $this->getProperty('ticker_url'))) {
                $this->setProperty('url', 'http://'.$this->getProperty('ticker_url'));
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

    return 'DigitalSignageBroadcastsCreateProcessor';

?>