<?php

/**
 * Digital Signage
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
 */

class DigitalSignageSlidesGetListProcessor extends modObjectGetListProcessor
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
    public $defaultSortField = 'DigitalSignageSlides.name';

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
        $broadcastID = $this->getProperty('broadcast_id');

        if (!empty($broadcastID)) {
            $criteria->innerJoin('DigitalSignageBroadcastsSlides', 'DigitalSignageBroadcastsSlides', [
                'DigitalSignageBroadcastsSlides.slide_id = DigitalSignageSlides.id'
            ]);

            $criteria->where([
                'DigitalSignageBroadcastsSlides.broadcast_id' => $broadcastID
            ]);
        }

        $query = $this->getProperty('query');

        if (!empty($query)) {
            $criteria->where([
                'DigitalSignageSlides.name:LIKE' => '%' . $query . '%'
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
            'data'              => unserialize($object->get('data')),
            'icon'              => 'file',
            'type_formatted'    => $object->get('type'),
            'broadcasts'        => []
        ]);

        $type = $object->getOne('getSlideType');

        if ($type) {
            if (!empty($type->get('icon'))) {
                $array['icon'] = $type->get('icon');
            }

            $translationKey = 'digitalsignage.slide_' . $type->get('key');

            if ($translationKey !== ($translation = $this->modx->lexicon($translationKey))) {
                $array['type_formatted'] = $translation;
            } else {
                $array['type_formatted'] = $type->get('name');
            }
        }

        foreach ((array) $object->getBroadcasts() as $key => $broadcast) {
            $array['broadcasts'][] = $broadcast->get('id');
        }

        if (in_array($object->get('editedon'), ['-001-11-30 00:00:00', '-1-11-30 00:00:00', '0000-00-00 00:00:00', null], true)) {
            $array['editedon'] = '';
        } else {
            $array['editedon'] = date($this->getProperty('dateFormat'), strtotime($object->get('editedon')));
        }

        if ($this->modx->hasPermission('digitalsignage_settings') || (int) $object->get('protected') === 0) {
            return $array;
        }

        return [];
    }
}

return 'DigitalSignageSlidesGetListProcessor';
