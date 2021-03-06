<?php

/**
 * Digital Signage
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
 */

class DigitalSignageBroadcastFeedsCreateProcessor extends modObjectCreateProcessor
{
    /**
     * @access public.
     * @var String.
     */
    public $classKey = 'DigitalSignageBroadcastsFeeds';

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

        if ($this->getProperty('published') === null) {
            $this->setProperty('published', 0);
        }

        return parent::initialize();
    }

    /**
    * @access public.
    * @return Mixed.
    */
    public function beforeSave()
    {
        if (!preg_match('/^(http|https)/si', $this->getProperty('url'))) {
            $this->setProperty('url', 'http://' . $this->getProperty('url'));
        }

        return parent::beforeSave();
    }

    /**
     * @access public.
     * @return Mixed.
     */
    public function afterSave()
    {
        $broadcast = $this->modx->getObject('DigitalSignageBroadcasts', [
            'id' => $this->object->get('broadcast_id')
        ]);

        if ($broadcast) {
            $broadcast->fromArray([
                'hash' => time()
            ]);

            $broadcast->save();
        }

        return parent::afterSave();
    }
}

return 'DigitalSignageBroadcastFeedsCreateProcessor';
