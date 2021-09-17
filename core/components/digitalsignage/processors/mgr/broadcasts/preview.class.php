<?php
@ini_set('display_errors', 1);
/**
 * Digital Signage
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
 */

class DigitalSignageBroadcastsUpdateProcessor extends modObjectGetProcessor
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

        $broadcast_id = (int)$this->getProperty('broadcast_id');
        if (!empty($broadcast_id)) {
            $this->setProperty('slide', $this->getProperty('id'));
            $this->setProperty($this->primaryKeyField, $broadcast_id);
        }

        return parent::initialize();
    }


    /**
     * @access public.
     * @return String.
     */
    public function process()
    {
        $resource = $this->object->getResource();
        $slide    = (int)$this->getProperty('slide');

        if ($resource) {
            $player = $this->modx->getObject('DigitalSignagePlayers', [
                'id' => $this->getProperty('player')
            ]);

            if ($player) {
                list($width, $height) = explode('x', $player->get('resolution'));

                return $this->success(null, array_merge($player->toArray(), [
                    'url'       => $this->modx->makeUrl($resource->get('id'), $resource->get('context_key'), [
                        'pl'        => $player->key,
                        'bc'        => $this->object->get('id'),
                        'preview'   => true,
                        'slide'     => $slide
                    ], 'full'),
                    'width'     => $width,
                    'height'    => $height
                ]));
            }
        }

        return $this->failure();
    }
}

return 'DigitalSignageBroadcastsUpdateProcessor';
