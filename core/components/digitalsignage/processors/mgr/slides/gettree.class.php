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
        public $languageTopics = ['digitalsignage:default'];

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
         * @return Mixed.
         */
        public function initialize() {
            $this->modx->getService('digitalsignage', 'DigitalSignage', $this->modx->getOption('digitalsignage.core_path', null, $this->modx->getOption('core_path') . 'components/digitalsignage/') . 'model/digitalsignage/');

            $this->setDefaultProperties([
            'limit' => 0
            ]);

            return parent::initialize();
        }

        /**
         * @access public.
         * @param Object $object.
         * @return Array.
         */
        public function prepareRow(xPDOObject $object) {
            $icon       = 'icon-file';
            $classes    = [];

            if (null !== ($type = $object->getOne('getSlideType'))) {
                if (!empty($type->get('icon'))) {
                    $icon = 'icon-' . $type->get('icon');
                }
            }

            if ((int) $object->get('published') === 0) {
                $classes[] = 'unpublished';
            }

            return [
                'id'        => 'create:' . $object->get('id'),
                'text'      => $object->get('name'),
                'pk'        => $this->getProperty('broadcast_id'),
                'cls'       => implode(' ', $classes),
                'iconCls'   => $icon,
                'loaded'    => true,
                'leaf'      => true
            ];
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