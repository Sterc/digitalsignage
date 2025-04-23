<?php

/**
 * Digital Signage
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
 */

class DigitalSignageSlidesTypesGetListProcessor extends modObjectGetListProcessor
{
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
    public $defaultSortField = 'id';

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
     * @return Mixed.
     */
    public function initialize()
    {
        $this->modx->getService('digitalsignage', 'DigitalSignage', $this->modx->getOption('digitalsignage.core_path', null, $this->modx->getOption('core_path') . 'components/digitalsignage/') . 'model/digitalsignage/');

        return parent::initialize();
    }

    /**
     * @access public.
     * @param xPDOObject $object.
     * @return Array.
     */
    public function prepareRow(xPDOObject $object)
    {
        $array = array_merge($object->toArray(), [
            'name_formatted'        => $object->get('name'),
            'description_formatted' => $object->get('description'),
            'data'                  => []
        ]);

        if (empty($object->get('icon'))) {
            $array['icon'] = 'file';
        }

        if (empty($object->get('name'))) {
            $translationKey = 'digitalsignage.slide_' . $object->get('key');

            if ($translationKey !== ($translation = $this->modx->lexicon($translationKey))) {
                $array['name_formatted'] = $translation;
            }
        }

        if (empty($object->get('description'))) {
            $translationKey = 'digitalsignage.slide_' . $object->get('key') . '_desc';

            if ($translationKey !== ($translation = $this->modx->lexicon($translationKey))) {
                $array['description_formatted'] = $translation;
            }
        }

        $sort   = [];
        $data   = (array) unserialize($object->get('data'));

        foreach ($data as $key => $row) {
            if (isset($row['menuindex'])) {
                $sort[$key] = $row['menuindex'];
            } else {
                $sort[$key] = $row['key'];
            }
        }

        array_multisort($sort, SORT_ASC, $data);

        $array['data'] = $data;

        return $array;
    }
}

return 'DigitalSignageSlidesTypesGetListProcessor';
