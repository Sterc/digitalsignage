<?php

    class DigitalSignageSlideTypesDataRemoveProcessor extends modProcessor {
        /**
         * @acces public.
         * @var String.
         */
        public $classKey = 'DigitalSignageSlidesTypes';

        /**
         * @acces public.
         * @var Array.
         */
        public $languageTopics = array('digitalsignage:default');

        /**
         * @acces public.
         * @var String.
         */
        public $objectType = 'digitalsignage.slidestypes';

        /**
         * @acces public.
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
         * @access public.
         * @return Mixed.
         */
        public function process() {
            if (null !== ($object = $this->modx->getObject($this->classKey, $this->getProperty('id')))) {
                $data = unserialize($object->data);

                if (!is_array($data)) {
                    $data = array();
                }

                if (isset($data[$this->getProperty('key')])) {
                    unset($data[$this->getProperty('key')]);
                }

                $object->fromArray(array(
                    'data' => serialize($data)
                ));

                if (!$object->save()) {
                    $this->addFieldError('key', $this->modx->lexicon('digitalsignage.error_slide_type_data'));
                } else {
                    return $this->success('', $object);
                }
            }

            return $this->failure($this->modx->lexicon('digitalsignage.error_slide_type_not_exists'));
        }
    }

    return 'DigitalSignageSlideTypesDataRemoveProcessor';

?>