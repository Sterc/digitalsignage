<?php

/**
* Digital Signage
*
* Copyright 2019 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
*/

class DigitalSignageSlidesBulkDepublishProcessor extends modProcessor
{
    /**
     * @access public.
     * @var Array.
     */
    public $languageTopics = ['digitalsignage:default'];

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
    public function process()
    {
        $ids = $this->getProperty('ids');

        if (empty($ids)) {
            return $this->failure($this->modx->lexicon('digitalsignage.slides_bulk_depublish_error_empty'));
        }

        $ids = explode(',', $ids);
        $ids = array_map('intval', $ids);
        $ids = array_filter($ids);

        if (empty($ids)) {
            return $this->failure($this->modx->lexicon('digitalsignage.slides_bulk_depublish_error_empty'));
        }

        $count = 0;
        $skipped = [];
        $errors = [];

        foreach ($ids as $id) {
            $slide = $this->modx->getObject('DigitalSignageSlides', $id);

            if ($slide) {
                if ((int) $slide->get('published') === 0) {
                    $skipped[] = $slide->get('name');
                    continue;
                }

                $slide->set('published', 0);

                if ($slide->save()) {
                    $count++;
                } else {
                    $errors[] = $slide->get('name');
                }
            }
        }

        $messages = [];

        if ($count > 0) {
            $messages[] = $this->modx->lexicon('digitalsignage.slides_bulk_depublish_success', ['count' => $count]);
        }

        if (!empty($skipped)) {
            $messages[] = $this->modx->lexicon('digitalsignage.slides_bulk_depublish_skipped', [
                'count' => count($skipped),
                'slides' => implode(', ', $skipped)
            ]);
        }

        if (!empty($errors)) {
            $messages[] = $this->modx->lexicon('digitalsignage.slides_bulk_depublish_error_failed', [
                'count' => count($errors),
                'slides' => implode(', ', $errors)
            ]);
        }

        $message = implode('<br>', $messages);

        if ($count === 0) {
            return $this->failure($message ?: $this->modx->lexicon('digitalsignage.slides_bulk_depublish_error_empty'));
        }

        return $this->success($message);
    }
}

return 'DigitalSignageSlidesBulkDepublishProcessor';
