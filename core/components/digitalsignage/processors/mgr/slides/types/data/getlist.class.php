<?php

    class DigitalSignageSlideTypesDataGetListProcessor extends modProcessor {
        /**
         * @access public.
         * @var String.
         */
        public $classKey = 'DigitalSignageSlidesTypes';

        /**
         * @access public.
         * @var Array.
         */
        public $languageTopics = array('digitalsignage:default', 'digitalsignage:slides');

        /**
         * @access public.
         * @var String.
         */
        public $defaultSortField = 'key';

        /**
         * @access public.
         * @var String.
         */
        public $defaultSortDirection = 'ASC';

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

            $this->setDefaultProperties(array(
                'start'     => 0,
                'limit'     => 20,
                'sort'      => $this->defaultSortField,
                'dir'       => $this->defaultSortDirection,
                'combo'     => false,
                'query'     => ''
            ));

            return parent::initialize();
        }


        /**
         * @access public.
         * @return Array.
         */
        public function process() {
            if (null !== ($object = $this->modx->getObject($this->classKey, $this->getProperty('id')))) {
                $output = array();
                $data   = unserialize($object->data);
                $query  = $this->getProperty('query');

                if (!is_array($data)) {
                    $data = array();
                }

                if ('key' == $this->getProperty('sort')) {
                    if ('DESC' == strtoupper($this->getProperty('dir'))) {
                        krsort($data);
                    } else {
                        ksort($data);
                    }
                }

                foreach ($data as $key => $value) {
                    if (!empty($query)) {
                        if (preg_match('/'.$query.'/', $key)) {
                            $output[] = array_merge(array(
                                'key' => $key
                            ), $value);
                        }
                    } else {
                        $output[] = array_merge(array(
                            'key' => $key
                        ), $value);
                    }
                }

                return $this->outputArray(array_slice($output, $this->getProperty('start'), $this->getProperty('limit')), count($output));
            }

            return $this->failure('Mislukt');
        }
    }

    return 'DigitalSignageSlideTypesDataGetListProcessor';

?>