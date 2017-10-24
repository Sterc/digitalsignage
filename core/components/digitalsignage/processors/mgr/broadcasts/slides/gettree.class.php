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
        public $languageTopics = array('digitalsignage:default');

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
         * @param Object $c.
         * @return Object.
         */
        public function prepareQueryBeforeCount(xPDOQuery $c) {
            $c->select($this->modx->getSelectColumns('DigitalSignageBroadcastsSlides', 'DigitalSignageBroadcastsSlides'));
            $c->select($this->modx->getSelectColumns('DigitalSignageSlides', 'DigitalSignageSlides', null, array('name', 'type', 'published')));

            //$c->innerjoin('DigitalSignageBroadcastsSlides', 'DigitalSignageBroadcastsSlides', array('DigitalSignageSlides.id = DigitalSignageBroadcastsSlides.slide_id'));
            $c->innerjoin('DigitalSignageSlides', 'DigitalSignageSlides', array('DigitalSignageBroadcastsSlides.slide_id = DigitalSignageSlides.id'));

            $broadcast = $this->getProperty('broadcast_id');

            if (!empty($broadcast)) {
                $c->where(array(
                    'DigitalSignageBroadcastsSlides.broadcast_id' => $broadcast
                ));
            }

            return $c;
        }

        /**
         * @access public.
         * @param Object $query.
         * @return Array.
         */
        public function prepareRow(xPDOObject $object) {
            $class 	= array();
            $icon 	= '';

            if (0 == $object->published) {
                $class[] = 'unpublished';
            }

            if (null !== ($slide = $object->getOne('getSlide'))) {
                if (null !== ($type = $slide->getOne('getSlideType'))) {
                    if (!empty($type->icon)) {
                        $icon = 'icon-'.$type->icon;
                    }
                }
            }

            return array(
                'id' 		=> 'n_slide:'.$object->slide_id.'_id:'.$object->id,
                'text' 		=> $object->name,
                'cls' 		=> implode(' ', $class),
                'iconCls' 	=> empty($icon) ? 'icon-file' : $icon,
                'loaded'	=> true,
                'leaf'		=> true,
                'data' 		=> array_merge($object->toArray(), array(
                    'c_id'		=> $object->id
                )),
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