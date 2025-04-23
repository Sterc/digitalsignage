<?php

/**
 * Digital Signage
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
 */

class DigitalSignageSlidesSortProcessor extends modProcessor
{
    /**
     * @access public.
     * @var String.
     */
    public $classKey = 'DigitalSignageBroadcastsSlides';

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
     * @return String.
     */
    public function process()
    {
        $data = urldecode($this->getProperty('data', ''));

        if (empty($data)) {
            $this->failure($this->modx->lexicon('invalid_data'));
        }

        $data = json_decode($data, true);

        if (empty($data)) {
            $this->failure($this->modx->lexicon('invalid_data'));
        }

        $nodes = [];

        $broadcast = $this->modx->getObject('DigitalSignageBroadcasts', [
            'id' => $this->getProperty('source_pk')
        ]);

        if ($broadcast) {
            $index = 1;

            foreach (array_keys($data) as $node) {
                if (false !== strpos($node, ':')) {
                    list($type, $id) = explode(':', $node);

                    if ('create' === $type) {
                        $slide = $this->modx->newObject('DigitalSignageBroadcastsSlides');

                        if ($slide) {
                            $slide->fromArray([
                                'broadcast_id'  => $broadcast->get('id'),
                                'slide_id'      => $id,
                                'sortindex'     => $index
                            ]);

                            if ($slide->save()) {
                                $nodes[] = [
                                    'old_id'    => $node,
                                    'new_id'    => 'update:' . $slide->get('id'),
                                    'clean_id'  => $slide->get('id')
                                ];
                            }
                        }
                    } else if ('update' === $type) {
                        $slide = $this->modx->getObject('DigitalSignageBroadcastsSlides', [
                            'id' => $id
                        ]);

                        if ($slide) {
                            $slide->fromArray([
                                'broadcast_id'  => $broadcast->get('id'),
                                'sortindex'     => $index
                            ]);

                            if ($slide->save()) {
                                $nodes[] = [
                                    'old_id'    => $node,
                                    'new_id'    => 'update:' . $slide->get('id'),
                                    'clean_id'  => $slide->get('id')
                                ];
                            }
                        }
                    }

                    $index++;
                }
            }

            $broadcast->fromArray([
                'hash' => time()
            ]);

            $broadcast->save();
        }

        return $this->outputArray($nodes);
    }
}

return 'DigitalSignageSlidesSortProcessor';
