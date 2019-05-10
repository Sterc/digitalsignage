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
    public $defaultSortField = 'name';

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
     * @param xPDOObject $object.
     * @return Array.
     */
    public function prepareRow(xPDOObject $object)
    {
        $icon       = 'icon-file';
        $classes    = [];
        $type       = $object->getOne('getSlideType');

        if ($type) {
            if (!empty($type->get('icon'))) {
                $icon = 'icon-' . $type->get('icon');
            }
        }

        if ((int) $object->get('published') === 0) {
            $classes[] = 'unpublished';
        }

        if ($this->modx->hasPermission('digitalsignage_settings') || (int) $object->get('protected') === 0) {
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

        return [];
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
