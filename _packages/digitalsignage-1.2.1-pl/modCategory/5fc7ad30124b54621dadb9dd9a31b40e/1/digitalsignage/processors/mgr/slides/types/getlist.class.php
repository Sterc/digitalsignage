<?php

/**
 * Digital Signage
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
 */

class DigitalSignageSlideTypesGetListProcessor extends modObjectGetListProcessor
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
    public $primaryKeyField = 'id';

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

        $this->setDefaultProperties([
            'dateFormat' => $this->modx->getOption('manager_date_format') . ', ' . $this->modx->getOption('manager_time_format')
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
        $query = $this->getProperty('query');

        if (!empty($query)) {
            $criteria->where([
                'key:LIKE'      => '%' . $query . '%',
                'OR:name:LIKE'  => '%' . $query . '%'
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
        $array = array_merge($object->toArray(), [
            'name_formatted'        => $object->get('name'),
            'description_formatted' => $object->get('description'),
            'data'                  => unserialize($object->get('data'))
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

        if (in_array($object->get('editedon'), ['-001-11-30 00:00:00', '-1-11-30 00:00:00', '0000-00-00 00:00:00', null], true)) {
            $array['editedon'] = '';
        } else {
            $array['editedon'] = date($this->getProperty('dateFormat'), strtotime($object->get('editedon')));
        }

        return $array;
    }
}

return 'DigitalSignageSlideTypesGetListProcessor';
