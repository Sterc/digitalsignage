<?php

    class DigitalSignageSlideTypesCreateProcessor extends modObjectCreateProcessor {
        /**
         * @access public.
         * @var String.
         */
        public $classKey = 'DigitalSignageSlidesTypes';

        /**
         * @access public.
         * @var Array.
         */
        public $languageTopics = ['digitalsignage:default'];

        /**
         * @access public.
         * @var String.
         */
        public $primaryKeyField = 'key';

        /**
         * @access public.
         * @var String.
         */
        public $objectType = 'digitalsignage.slidestypes';

        /**
         * @access public.
         * @return Mixed.
         */
        public function initialize() {
            $this->modx->getService('digitalsignage', 'DigitalSignage', $this->modx->getOption('digitalsignage.core_path', null, $this->modx->getOption('core_path') . 'components/digitalsignage/') . 'model/digitalsignage/');

            if (null !== ($key = $this->getProperty('key'))) {
                $this->setProperty('key', strtolower(str_replace([' ', '-'], '_', $key)));
            }

            return parent::initialize();
        }

        /**
         * @access public.
         * @return Mixed.
         */
        public function beforeSave() {
            $this->object->set('key', $this->getProperty('key'));

            $c = [
                'key' => $this->getProperty('key')
            ];

            if (!preg_match('/^([a-zA-Z0-9\_\-]+)$/si', $this->getProperty('key'))) {
                $this->addFieldError('key', $this->modx->lexicon('digitalsignage.error_slide_type_character'));
            } else if ($this->doesAlreadyExist($c)) {
                $this->addFieldError('key', $this->modx->lexicon('digitalsignage.error_slide_type_exists'));
            }

            return parent::beforeSave();
        }
    }

    return 'DigitalSignageSlideTypesCreateProcessor';

?>