<?php

    class DigitalSignageSlideTypesDataSortProcessor extends modProcessor {
        /**
         * @access public.
         * @var String.
         */
        public $classKey = 'DigitalSignageSlidesTypes';

        /**
         * @access public.
         * @var Array.
         */
        public $languageTopics = ['digitalsignage:default', 'digitalsignage:slides'];

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
            $this->modx->getService('digitalsignage', 'DigitalSignage', $this->modx->getOption('digitalsignage.core_path', null, $this->modx->getOption('core_path') .' components/digitalsignage/') . 'model/digitalsignage/');

            return parent::initialize();
        }

        /**
         * @access public.
         * @return Array.
         */
        public function process() {
            if (null !== ($sort = $this->modx->fromJSON($this->getProperty('sort')))) {
                $newSort = [];

                foreach ($sort as $value) {
                    $newSort[$value['key']] = $value['menuindex'];
                }

                if (null !== ($object = $this->modx->getObject($this->classKey, $this->getProperty('id')))) {
                    $data = unserialize($object->get('data'));

                    if (!is_array($data)) {
                        $data = [];
                    }

                    foreach ($data as $key => $value) {
                        if (isset($newSort[$key])) {
                            $data[$key]['menuindex'] = $newSort[$key];
                        } else {
                            $data[$key]['menuindex'] = 0;
                        }
                    }

                    $object->fromArray([
                        'data' => serialize($data)
                    ]);

                    if (!$object->save()) {
                        return $this->failure($this->modx->lexicon('digitalsignage.error_slide_type_data'));
                    } else {
                        return $this->success('', $object);
                    }
                }

                return $this->failure($this->modx->lexicon('digitalsignage.error_slide_type_not_exists'));
            }
        }
    }

    return 'DigitalSignageSlideTypesDataSortProcessor';

?>