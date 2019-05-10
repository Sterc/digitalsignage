<?php

/**
 * Digital Signage
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
 */

class DigitalSignageBroadcastsGetListProcessor extends modObjectGetListProcessor
{
    /**
     * @access public.
     * @var String.
     */
    public $classKey = 'DigitalSignageBroadcasts';

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
    public $objectType = 'digitalsignage.broadcasts';

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
        $criteria->select($this->modx->getSelectColumns('DigitalSignageBroadcasts', 'DigitalSignageBroadcasts'));
        $criteria->select($this->modx->getSelectColumns('modResource', 'modResource', null, ['pagetitle', 'description', 'template']));

        $criteria->innerJoin('modResource', 'modResource', [
            'modResource.id = DigitalSignageBroadcasts.resource_id'
        ]);

        $query = $this->getProperty('query');

        if (!empty($query)) {
            $criteria->where([
                'modResource.pagetitle:LIKE' => '%' . $query . '%'
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
            'name'              => $object->get('pagetitle'),
            'name_formatted'    => $object->get('pagetitle') . ($this->modx->hasPermission('tree_show_resource_ids') ? ' (' . $object->get('resource_id') . ')' : ''),
            'slides'            => count($object->getSlides()),
            'feeds'             => count($object->getFeeds()),
            'players'           => [],
            'sync'              => [
                'valid'             => !$object->needSync(),
                'timestamp'         => $object->getLastSync()
            ]
        ]);

        foreach ((array) $object->getPlayers() as $player) {
            $array['players'][] = [
                'key'       => $player->get('key'),
                'name'      => $player->get('name'),
                'online'        => $player->isOnline($object->get('id'))
            ];
        }

        if (in_array($array['sync']['timestamp'], ['-001-11-30 00:00:00', '-1-11-30 00:00:00', '0000-00-00 00:00:00', null], true)) {
            $array['sync']['timestamp'] = '';
        } else {
            $array['sync']['timestamp'] = date($this->getProperty('dateFormat'), strtotime($array['sync']['timestamp']));
        }

        if (in_array($array['editedon'], ['-001-11-30 00:00:00', '-1-11-30 00:00:00', '0000-00-00 00:00:00', null], true)) {
            $array['editedon'] = '';
        } else {
            $array['editedon'] = date($this->getProperty('dateFormat'), strtotime($array['editedon']));
        }

        if ($this->modx->hasPermission('digitalsignage_settings') || (int) $object->get('protected') === 0) {
            return $array;
        }

        return [];
    }
}

return 'DigitalSignageBroadcastsGetListProcessor';
