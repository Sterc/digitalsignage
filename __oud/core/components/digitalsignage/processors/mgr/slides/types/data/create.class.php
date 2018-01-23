<?php

    class DigitalSignageSlideTypesDataCreateProcessor extends modProcessor {
        /**
         * @access public.
         * @var String.
         */
        public $classKey = 'DigitalSignageSlidesTypes';

        /**
         * @access public.
         * @var Array.
         */
        public $languageTopics = array('digitalsignage:default');

        /**
         * @access public.
         * @var String.
         */
        public $objectType = 'digitalsignage.slidestypes';

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

            if (null !== ($key = $this->getProperty('key'))) {
                $this->setProperty('key', strtolower(str_replace(array(' ', '-'), '_', $key)));
            }

            return parent::initialize();
        }

        /**
         * @access public.
         * @return Mixed.
         */
        public function process() {
            $criteria = array(
                'key' => $this->getProperty('key')
            );

            if (null !== ($object = $this->modx->getObject($this->classKey, $this->getProperty('id')))) {
                if (!preg_match('/^([a-zA-Z0-9\_\-]+)$/si', $this->getProperty('key'))) {
                    $this->addFieldError('key', $this->modx->lexicon('digitalsignage.error_slide_type_data_character'));
                } else {
                    $data = unserialize($object->data);

                    if (!is_array($data)) {
                        $data = array();
                    }

                    if (isset($data[$this->getProperty('key')])) {
                        $this->addFieldError('key', $this->modx->lexicon('digitalsignage.error_slide_type_date_exists'));
                    } else {
                        $object->fromArray(array(
                           'data' => serialize(array_merge($data, array(
                               $this->getProperty('key') => array(
                                   'xtype'         => $this->getProperty('xtype'),
                                   'value'         => $this->getProperty('value'),
                                   'label'         => $this->getProperty('label'),
                                   'description'   => $this->getProperty('description')
                               )
                           )))
                        ));

                        if (!$object->save()) {
                            $this->addFieldError('key', $this->modx->lexicon('digitalsignage.error_slide_type_not_exists'));
                        } else {
                            return $this->success('', $object);
                        }
                    }
                }
            } else {
                $this->addFieldError('key', $this->modx->lexicon('digitalsignage.error_slide_type_not_exists'));
            }

            return $this->failure();
        }
    }

    return 'DigitalSignageSlideTypesDataCreateProcessor';

?>