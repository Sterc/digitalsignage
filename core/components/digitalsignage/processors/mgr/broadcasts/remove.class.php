<?php

/**
 * Digital Signage
 *
 * Copyright 2019 by Sterc <modx@sterc.nl>
 */

class DigitalSignageBroadcastsRemoveProcessor extends modObjectRemoveProcessor
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
    public $objectType = 'digitalsignage.broadcasts';

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
        $resourceResponse = $this->modx->runProcessor('resource/delete', array(
            'id' => $this->object->get('resource_id')
        ));

        if ($resourceResponse) {
            if (!$resourceResponse->isError()) {
                $object = $this->modx->getObject('modResource', [
                    'id' => $this->object->get('resource_id')
                ]);

                if ($object) {
                    $object->remove();
                }
            }
        }

        return parent::beforeRemove();
    }

    /**
     * @access public.
     * @return Mixed.
     */
    public function afterRemove()
    {
        foreach ($this->object->getMany('getSlides') as $slide) {
            $slide->remove();
        }

        foreach ($this->object->getMany('getFeeds') as $feed) {
            $feed->remove();
        }

        foreach ($this->object->getMany('getSchedules') as $schedulde) {
            $schedulde->remove();
        }

        return parent::afterRemove();
    }
}

return 'DigitalSignageBroadcastsRemoveProcessor';
