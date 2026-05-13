<?php

/**
* Digital Signage
*
* Copyright 2019 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
*/

class DigitalSignageSlidesRemoveProcessor extends modObjectRemoveProcessor
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
    public $objectType = 'digitalsignage.slides';

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
     * @return Mixed.
     */
    public function beforeRemove()
    {
        $broadcasts = $this->object->getMany('getBroadcasts');
        $broadcastCount = count($broadcasts);

        if ($broadcastCount > 0) {
            $broadcastNames = [];
            foreach ($broadcasts as $broadcast) {
                if ($broadcastObject = $broadcast->getOne('broadcast')) {
                    $broadcastNames[] = $broadcastObject->get('name');
                }
            }

            return $this->modx->lexicon('digitalsignage.slide_remove_error_has_broadcasts', [
                'count' => $broadcastCount,
                'broadcasts' => implode(', ', $broadcastNames)
            ]);
        }

        return parent::beforeRemove();
    }
}

return 'DigitalSignageSlidesRemoveProcessor';
