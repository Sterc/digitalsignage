<?php

/**
 * Digital Signage
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
 */

class DigitalSignageSlideTypesDataSortProcessor extends modProcessor
{
    /**
     * @access public.
     * @var String.
     */
    public $classKey = 'DigitalSignageSlidesTypes';

    /**
     * @access public.
     * @var Array.
     */
    public $languageTopics = ['digitalsignage:default', 'digitalsignage:slides'];

    /**
     * @access public.
     * @var String.
     */
    public $objectType = 'digitalsignage.slidestypes';

    /**
     * @access public.
     * @return Mixed.
     */
    public function initialize()
    {
        $this->modx->getService('digitalsignage', 'DigitalSignage', $this->modx->getOption('digitalsignage.core_path', null, $this->modx->getOption('core_path') .' components/digitalsignage/') . 'model/digitalsignage/');

        return parent::initialize();
    }

    /**
     * @access public.
     * @return String.
     */
    public function process()
    {
        $sort = json_decode($this->getProperty('sort'), true);

        if ($sort) {
            $newSort = [];

            foreach ((array) $sort as $value) {
                $newSort[$value['key']] = $value['menuindex'];
            }

            $object = $this->modx->getObject($this->classKey, [
                'id' => $this->getProperty('id')
            ]);

            if ($object) {
                $data = (array) unserialize($object->get('data'));

                foreach ($data as $key => $value) {
                    if (isset($newSort[$key])) {
                        $data[$key]['menuindex'] = $newSort[$key];
                    } else {
                        $data[$key]['menuindex'] = 0;
                    }
                }

                $object->fromArray([
                    'data' => serialize($data)
                ]);

                if (!$object->save()) {
                    return $this->failure($this->modx->lexicon('digitalsignage.error_slide_type_data'));
                }

                return $this->success('', $object);
            }

            return $this->failure($this->modx->lexicon('digitalsignage.error_slide_type_not_exists'));
        }
    }
}

return 'DigitalSignageSlideTypesDataSortProcessor';
