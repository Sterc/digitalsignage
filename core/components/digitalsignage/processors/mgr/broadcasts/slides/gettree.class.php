<?php

    class DigitalSignageSlidesGetTreeProcessor extends modObjectGetListProcessor {
        /**
        * @access public.
        * @var String.
        */
        public $classKey = 'DigitalSignageBroadcastsSlides';

        /**
        * @access public.
        * @var Array.
        */
        public $languageTopics = ['digitalsignage:default'];

        /**
        * @access public.
        * @var String.
        */
        public $defaultSortField = 'sortindex';

        /**
        * @access public.
        * @var String.
        */
        public $defaultSortDirection = 'ASC';

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
        * @param Object $c.
        * @return Object.
        */
        public function prepareQueryBeforeCount(xPDOQuery $c) {
            $c->select($this->modx->getSelectColumns('DigitalSignageBroadcastsSlides', 'DigitalSignageBroadcastsSlides'));
            $c->select($this->modx->getSelectColumns('DigitalSignageSlides', 'DigitalSignageSlides', null, ['name', 'type', 'published']));

            $c->innerJoin('DigitalSignageSlides', 'DigitalSignageSlides', 'DigitalSignageBroadcastsSlides.slide_id = DigitalSignageSlides.id');

            $broadcastID = $this->getProperty('broadcast_id');

            if (!empty($broadcastID)) {
                $c->where([
                    'DigitalSignageBroadcastsSlides.broadcast_id' => $broadcastID
                ]);
            }

            return $c;
        }

        /**
        * @access public.
        * @param Object $object.
        * @return Array.
        */
        public function prepareRow(xPDOObject $object) {
            $icon       = 'icon-file';
            $classes    = [];

            if (null !== ($slide = $object->getOne('getSlide'))) {
                if (null !== ($type = $slide->getOne('getSlideType'))) {
                    if (!empty($type->get('icon'))) {
                        $icon = 'icon-' . $type->get('icon');
                    }
                }
            }

            if ((int) $object->get('published') === 0) {
                $classes[] = 'unpublished';
            }

            return [
                'id'        => 'update:' . $object->get('id'),
                'clean_id'  => $object->get('id'),
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