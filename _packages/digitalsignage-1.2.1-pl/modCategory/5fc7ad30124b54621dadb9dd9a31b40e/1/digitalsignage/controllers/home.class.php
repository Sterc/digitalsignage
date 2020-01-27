<?php

/**
 * Digital Signage
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
 */

require_once dirname(__DIR__) . '/index.class.php';

class DigitalSignageHomeManagerController extends DigitalSignageManagerController
{
    /**
     * @access public.
     */
    public function loadCustomCssJs()
    {
        $this->addCss($this->modx->digitalsignage->config['assets_url'] . 'libs/colorpicker/css/Ext.ux.ColorField.css');
        $this->addCss($this->modx->digitalsignage->config['assets_url'] . 'libs/extensible/css/extensible-all.css');
        $this->addCss($this->modx->digitalsignage->config['assets_url'] . 'libs/extensible/css/extensible-default.css');

        $this->addJavascript($this->modx->digitalsignage->config['js_url'] . 'mgr/widgets/home.panel.js');
        $this->addJavascript($this->modx->digitalsignage->config['js_url'] . 'mgr/widgets/broadcasts.grid.js');
        $this->addJavascript($this->modx->digitalsignage->config['js_url'] . 'mgr/widgets/broadcasts.feeds.grid.js');
        $this->addJavascript($this->modx->digitalsignage->config['js_url'] . 'mgr/widgets/slides.grid.js');
        $this->addJavascript($this->modx->digitalsignage->config['js_url'] . 'mgr/widgets/players.grid.js');
        $this->addJavascript($this->modx->digitalsignage->config['js_url'] . 'mgr/widgets/players.schedules.grid.js');
        $this->addJavascript($this->modx->digitalsignage->config['js_url'] . 'mgr/widgets/slides.tree.js');

        $this->addJavascript($this->modx->digitalsignage->config['js_url'] . 'mgr/widgets/slides.types.grid.js');

        $this->addJavascript($this->modx->digitalsignage->config['assets_url'] . 'libs/colorpicker/js/Ext.ux.ColorField.js');
        $this->addJavascript($this->modx->digitalsignage->config['assets_url'] . 'libs/extensible/js/extensible-all.js');

        $this->addLastJavascript($this->modx->digitalsignage->config['js_url'] . 'mgr/sections/home.js');
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
        return $this->modx->digitalsignage->config['templates_path'] . 'home.tpl';
    }

    /**
     * @access public.
     * @param Array $scriptProperties.
     */
    public function process(array $scriptProperties = [])
    {
        $useEditor = $this->modx->getOption('use_editor');
        $whichEditor = $this->modx->getOption('which_editor');

        if ($useEditor && !empty($whichEditor)) {
            $onRichTextEditorInit = $this->modx->invokeEvent('OnRichTextEditorInit', [
                'editor'    => $whichEditor,
                'elements'  => []
            ]);

            if (is_array($onRichTextEditorInit)) {
                $onRichTextEditorInit = implode('', $onRichTextEditorInit);
            }

            $this->setPlaceholder('onRichTextEditorInit', $onRichTextEditorInit);
        }
    }
}
