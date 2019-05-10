<?php

/**
 * Digital Signage
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
 */

class DigitalSignageBroadcastsCreateProcessor extends modObjectCreateProcessor
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

        if ($this->modx->hasPermission('digitalsignage_settings')) {
            if ($this->getProperty('protected') === null) {
                $this->setProperty('protected', 0);
            } else {
                $this->setProperty('protected', 1);
            }
        } else {
            $this->unsetProperty('protected');
        }

        return parent::initialize();
    }

    /**
     * @access public.
     * @return Mixed.
     */
    public function beforeSave()
    {
        $exists = $this->modx->getCount('modResource', [
            'id:!='         => $this->object->get('resource_id'),
            'pagetitle'     => $this->getProperty('name'),
            'context_key'   => $this->modx->getOption('digitalsignage.context')
        ]);

        if ($exists >= 1) {
            $this->addFieldError('name', $this->modx->lexicon('digitalsignage.error_broadcast_resource_exists'));
        } else {
            $this->object->set('has', time());
            $this->object->set('color', mt_rand(1, 32));

            $resourceResponse = $this->modx->runProcessor('resource/create', [
                'pagetitle'     => $this->getProperty('name'),
                'description'   => $this->getProperty('description'),
                'alias'         => $this->getProperty('name'),
                'context_key'   => $this->modx->getOption('digitalsignage.context'),
                'template'      => $this->getProperty('template'),
                'show_in_tree'  => 0,
                'published'     => 1
            ]);

            if ($resourceResponse->isError()) {
                foreach ((array) $resourceResponse->getFieldErrors() as $error) {
                    $this->addFieldError('name', $error->message);
                }
            } else {
                $resource = $resourceResponse->getObject();

                if ($resource) {
                    if (isset($resource['id'])) {
                        $this->object->set('resource_id', $resource['id']);
                    } else {
                        $this->addFieldError('name', $this->modx->lexicon('digitalsignage.error_broadcast_resource_object'));
                    }
                } else {
                    $this->addFieldError('name', $this->modx->lexicon('digitalsignage.error_broadcast_resource_object'));
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

return 'DigitalSignageBroadcastsCreateProcessor';
