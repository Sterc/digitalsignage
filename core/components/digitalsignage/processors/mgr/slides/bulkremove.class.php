<?php

/**
* Digital Signage
*
* Copyright 2019 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
*/

class DigitalSignageSlidesBulkRemoveProcessor extends modProcessor
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
            return $this->failure($this->modx->lexicon('digitalsignage.slides_bulk_remove_error_empty'));
        }

        $ids = explode(',', $ids);
        $ids = array_map('intval', $ids);
        $ids = array_filter($ids);

        if (empty($ids)) {
            return $this->failure($this->modx->lexicon('digitalsignage.slides_bulk_remove_error_empty'));
        }

        $count = 0;
        $errors = [];
        $skipped = [];

        foreach ($ids as $id) {
            $slide = $this->modx->getObject('DigitalSignageSlides', $id);

            if ($slide) {
                // Check if slide has broadcast relationships
                $broadcasts = $slide->getMany('getBroadcasts');
                $broadcastCount = count($broadcasts);

                if ($broadcastCount > 0) {
                    // Get broadcast names for error message
                    $broadcastNames = [];
                    foreach ($broadcasts as $broadcast) {
                        if ($broadcastObject = $broadcast->getOne('broadcast')) {
                            $broadcastNames[] = $broadcastObject->get('name');
                        }
                    }

                    $skipped[] = [
                        'id' => $id,
                        'name' => $slide->get('name'),
                        'broadcasts' => implode(', ', $broadcastNames)
                    ];
                    continue;
                }

                if ($slide->remove()) {
                    $count++;
                } else {
                    $errors[] = [
                        'id' => $id,
                        'name' => $slide->get('name')
                    ];
                }
            }
        }

        // Build detailed message
        $messages = [];

        if ($count > 0) {
            $messages[] = $this->modx->lexicon('digitalsignage.slides_bulk_remove_success', ['count' => $count]);
        }

        if (!empty($skipped)) {
            $skippedDetails = [];
            foreach ($skipped as $skip) {
                $skippedDetails[] = $skip['name'] . ' (' . $skip['broadcasts'] . ')';
            }
            $messages[] = $this->modx->lexicon('digitalsignage.slides_bulk_remove_error_has_broadcasts', [
                'count' => count($skipped),
                'slides' => implode('; ', $skippedDetails)
            ]);
        }

        if (!empty($errors)) {
            $errorDetails = [];
            foreach ($errors as $error) {
                $errorDetails[] = $error['name'];
            }
            $messages[] = $this->modx->lexicon('digitalsignage.slides_bulk_remove_error_failed', [
                'count' => count($errors),
                'slides' => implode(', ', $errorDetails)
            ]);
        }

        $message = implode('<br>', $messages);

        // Return failure if nothing was deleted
        if ($count === 0) {
            return $this->failure($message);
        }

        // Return success with warning if some were deleted
        return $this->success($message);
    }
}

return 'DigitalSignageSlidesBulkRemoveProcessor';
