<?php

    class DigitalSignageSlidesGetTreeProcessor extends modObjectGetListProcessor {
        /**
         * @access public.
         * @var String.
         */
        public $classKey = 'DigitalSignageSlides';

        /**
         * @access public.
         * @var Array.
         */
        public $languageTopics = array('digitalsignage:default');

        /**
         * @access public.
         * @var String.
         */
        public $defaultSortField = 'id';

        /**
         * @access public.
         * @var String.
         */
        public $defaultSortDirection = 'DESC';

        /**
         * @access public.
         * @var String.
         */
        public $objectType = 'digitalsignage.slides';

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
                'limit' => 0
            ));

            return parent::initialize();
        }

        /**
         * @access public.
         * @param Object $query.
         * @return Array.
         */
        public function prepareRow(xPDOObject $object) {
            $class 	= array();
            $icon	= '';

            if (0 == $object->published) {
                $class[] = 'unpublished';
            }

            if (null !== ($type = $object->getOne('getSlideType'))) {
                if (!empty($type->icon)) {
                    $icon = 'icon-'.$type->icon;
                }
            }

            return array(
                'id' 		=> 'n_slide:'.$object->id,
                'text' 		=> $object->name,
                'cls' 		=> implode(' ', $class),
                'iconCls' 	=> empty($icon) ? 'icon-file' : $icon,
                'loaded'	=> true,
                'leaf'		=> true,
                'data' 		=> $object->toArray(),
                'pk'		=> $this->getProperty('broadcast_id')
            );
        }

        /**
         * @access public.
         * @param Array $array.
         * @param Boolean $count.
         * @return String.
         */
        public function outputArray(array $array, $count = false) {
            return $this->modx->toJSON($array);
        }
    }

    return 'DigitalSignageSlidesGetTreeProcessor';

?>