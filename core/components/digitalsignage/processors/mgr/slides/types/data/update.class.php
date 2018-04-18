<?php

    class DigitalSignageSlideTypesDataUpdateProcessor extends modObjectUpdateProcessor {
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
        public function process() {
            if (null !== ($object = $this->modx->getObject($this->classKey, $this->getProperty('id')))) {
                if (!preg_match('/^([a-zA-Z0-9\_\-]+)$/si', $this->getProperty('key')) || is_numeric($this->getProperty('key'))) {
                    $this->addFieldError('key', $this->modx->lexicon('digitalsignage.error_slide_type_data_character'));
                } else {
                    $data = unserialize($object->get('data'));

                    if (!is_array($data)) {
                        $data = [];
                    }

                    $object->fromArray([
                        'data' => serialize(array_merge($data, [
                            $this->getProperty('key') => array_merge($data[$this->getProperty('key')], [
                                'xtype'          => $this->getProperty('xtype'),
                                'default_value'  => $this->getProperty('default_value'),
                                'label'          => $this->getProperty('label'),
                                'description'    => $this->getProperty('description')
                            ])
                        ]))
                    ]);

                    if (!$object->save()) {
                        $this->addFieldError('key', $this->modx->lexicon('digitalsignage.error_slide_type_data'));
                    } else {
                        return $this->success('', $object);
                    }
                }

                return $this->failure('', $object);
            }

            return $this->failure($this->modx->lexicon('digitalsignage.error_slide_type_not_exists'));
        }
    }

    return 'DigitalSignageSlideTypesDataUpdateProcessor';

?>