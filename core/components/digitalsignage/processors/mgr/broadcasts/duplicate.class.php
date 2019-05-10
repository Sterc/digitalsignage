<?php

/**
 * Digital Signage
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
 */

class DigitalSignageBroadcastsDuplicateProcessor extends modObjectDuplicateProcessor
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
     * @return Mixed.
     */
    public function beforeSave()
    {
        $this->newObject->set('has', time());
        $this->newObject->set('color', mt_rand(1, 32));

        $resourceResponse = $this->modx->runProcessor('resource/duplicate', [
            'id'    => $this->object->get('resource_id'),
            'name'  => $this->newObject->get('name')
        ]);

        if ($resourceResponse->isError()) {
            foreach ((array) $resourceResponse->getFieldErrors() as $error) {
                $this->addFieldError('name', $error->message);
            }
        } else {
            $resource = $resourceResponse->getObject();

            if ($resource) {
                if (isset($object['id'])) {
                    $this->newObject->set('resource_id', $resource['id']);
                } else {
                    $this->addFieldError('name', $this->modx->lexicon('error_resource_object'));
                }
            } else {
                $this->addFieldError('name', $this->modx->lexicon('error_resource_object'));
            }

            $tickers = [];

            foreach (array_filter(explode(',', $this->getProperty('ticker_url'))) as $ticker) {
                if (!preg_match('/^(http|https)/si', $ticker)) {
                    $tickers[] = 'http://' . trim($ticker);
                } else {
                    $tickers[] = $ticker;
                }
            }

            $this->object->set('ticker_url', implode(',', array_unique($tickers)));

            foreach ($this->object->getMany('getSlides') as $slide) {
                if (null !== ($object = $this->modx->newObject('DigitalSignageBroadcastsSlides'))) {
                    $object->fromArray($slide->toArray());

                    $this->newObject->addMany($object);
                }
            }

            foreach ($this->object->getMany('getFeeds') as $feed) {
                if (null !== ($object = $this->modx->newObject('DigitalSignageBroadcastsFeeds'))) {
                    $object->fromArray($feed->toArray());

                    $this->newObject->addMany($object);
                }
            }
        }

        return parent::beforeSave();
    }

    /**
     * @access public.
     * @return Mixed.
     */
    public function afterSave()
    {
        $this->modx->cacheManager->refresh([
            'db'                => [],
            'auto_publish'      => [
                'contexts'          => [
                    $this->modx->getOption('digitalsignage.context')
                ]
            ],
            'context_settings'  => [
                'contexts'          => [
                    $this->modx->getOption('digitalsignage.context')
                ]
            ],
            'resource'          => [
                'contexts'          => [
                    $this->modx->getOption('digitalsignage.context')
                ]
            ]
        ]);

        $this->modx->call('modResource', 'refreshURIs', [&$this->modx, 0, [
            'contexts' => $this->modx->getOption('digitalsignage.context')
        ]]);

        return parent::afterSave();
    }
}

return 'DigitalSignageBroadcastsDuplicateProcessor';
