<?php

/**
 * Digital Signage
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
 */

class DigitalSignageSlidesGetTreeProcessor extends modObjectGetListProcessor
{
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
    public function initialize()
    {
        $this->modx->getService('digitalsignage', 'DigitalSignage', $this->modx->getOption('digitalsignage.core_path', null, $this->modx->getOption('core_path') . 'components/digitalsignage/') . 'model/digitalsignage/');

        $this->setDefaultProperties([
            'limit' => 0
        ]);

        return parent::initialize();
    }

    /**
    * @access public.
    * @param xPDOQuery $criteria.
    * @return xPDOQuery.
    */
    public function prepareQueryBeforeCount(xPDOQuery $criteria)
    {
        $criteria->select($this->modx->getSelectColumns('DigitalSignageBroadcastsSlides', 'DigitalSignageBroadcastsSlides'));
        $criteria->select($this->modx->getSelectColumns('DigitalSignageSlides', 'DigitalSignageSlides', null, ['name', 'type', 'published']));

        $criteria->innerJoin('DigitalSignageSlides', 'DigitalSignageSlides', [
            'DigitalSignageBroadcastsSlides.slide_id = DigitalSignageSlides.id'
        ]);

        $broadcastID = $this->getProperty('broadcast_id');

        if (!empty($broadcastID)) {
            $criteria->where([
                'DigitalSignageBroadcastsSlides.broadcast_id' => $broadcastID
            ]);
        }

        return $criteria;
    }

    /**
    * @access public.
    * @param xPDOObject $object.
    * @return Array.
    */
    public function prepareRow(xPDOObject $object)
    {
        $icon       = 'icon-file';
        $classes    = [];
        $slide      = $object->getOne('getSlide');

        if ($slide) {
            $type = $slide->getOne('getSlideType');

            if ($type) {
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
    public function outputArray(array $array, $count = false)
    {
        return json_encode($array);
    }
}

return 'DigitalSignageSlidesGetTreeProcessor';
