<?php

/**
 * Digital Signage
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
 */

require_once dirname(__DIR__) . '/index.class.php';

class DigitalSignageAdminManagerController extends DigitalSignageManagerController
{
    /**
     * @access public.
     */
    public function loadCustomCssJs() {
        $this->addJavascript($this->modx->digitalsignage->config['js_url'] . 'mgr/widgets/admin.panel.js');

        $this->addJavascript($this->modx->digitalsignage->config['js_url'] . 'mgr/widgets/slides.types.grid.js');
        $this->addJavascript($this->modx->digitalsignage->config['js_url'] . 'mgr/widgets/slides.types.data.grid.js');

        $this->addLastJavascript($this->modx->digitalsignage->config['js_url'] . 'mgr/sections/admin.js');
    }

    /**
     * @access public.
     * @return String.
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('digitalsignage');
    }

    /**
     * @access public.
     * @return String.
     */
    public function getTemplateFile()
    {
        return $this->modx->digitalsignage->config['templates_path'] . 'admin.tpl';
    }

    /**
     * @access public.
     * @returns Boolean.
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('digitalsignage_admin');
    }
}
