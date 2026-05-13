<?php

/**
 * Digital Signage
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
 */

class DigitalSignagePlayersCreateProcessor extends modObjectCreateProcessor
{
    /**
     * @access public.
     * @var String.
     */
    public $classKey = 'DigitalSignagePlayers';

    /**
     * @access public.
     * @var Array.
     */
    public $languageTopics = ['digitalsignage:default'];

    /**
     * @access public.
     * @var String.
     */
    public $objectType = 'digitalsignage.players';

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
        if ($this->getProperty('key') === null) {
            $unique = false;

            while (!$unique) {
                $key = $this->generatePlayerKey();

                $object = $this->modx->getObject('DigitalSignagePlayers', [
                    'key' => $key
                ]);

                if (!$object) {
                    $this->object->set('key', $key);

                    $unique = true;
                }
            }
        }

        if (!preg_match('/^(\d+)x(\d+)$/', $this->getProperty('resolution'))) {
            $this->addFieldError('resolution', $this->modx->lexicon('digitalsignage.error_player_resolution'));
        }

        return parent::beforeSave();
    }

    /**
     * @access public.
     * @return String.
     */
    public function generatePlayerKey()
    {
        $key = '';
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        for ($i = 0; $i < 12; $i++) {
            $key .= $characters[mt_rand(0, strlen($characters) - 1)];
        }

        return implode(':', str_split($key, 3));
    }
}

return 'DigitalSignagePlayersCreateProcessor';
