<?php

/**
 * Digital Signage
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
 */

class DigitalSignageBroadcastsSynchronizeSelectedProcessor extends modProcessor
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
    public $languageTopics = array('digitalsignage:default');

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
     * @return String.
     */
    public function process()
    {
        foreach (explode(',' , $this->getProperty('ids')) as $id) {

            $object = $this->modx->getObject($this->classKey, [
                'id' => $id
            ]);

            if ($object) {
                if ($object->hasResource() && !$object->sync()) {
                    return $this->failure($this->modx->lexicon('digitalsignage.error_broadcast_sync'));
                }
            }
        }

        return $this->outputArray([]);
    }
}

return 'DigitalSignageBroadcastsSynchronizeSelectedProcessor';
