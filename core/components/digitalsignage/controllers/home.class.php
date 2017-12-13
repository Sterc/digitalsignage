<?php

	class DigitalSignageHomeManagerController extends DigitalSignageManagerController {
	
	    /**
	     * @access public.
	     */
	    public function loadCustomCssJs() {
	        $this->addCss($this->digitalsignage->config['css_url'].'mgr/digitalsignage.css');
	        $this->addCss($this->digitalsignage->config['assets_url'].'libs/extensible/css/extensible-all.css');
	        $this->addCss($this->digitalsignage->config['assets_url'].'libs/extensible/css/extensible-default.css');
	
	        $this->addJavascript($this->digitalsignage->config['js_url'].'mgr/widgets/home.panel.js');
	        $this->addJavascript($this->digitalsignage->config['js_url'].'mgr/widgets/broadcasts.grid.js');
	        $this->addJavascript($this->digitalsignage->config['js_url'].'mgr/widgets/broadcasts.feeds.grid.js');
	        $this->addJavascript($this->digitalsignage->config['js_url'].'mgr/widgets/slides.grid.js');
	        $this->addJavascript($this->digitalsignage->config['js_url'].'mgr/widgets/players.grid.js');
	        $this->addJavascript($this->digitalsignage->config['js_url'].'mgr/widgets/players.schedules.grid.js');
	        $this->addJavascript($this->digitalsignage->config['js_url'].'mgr/widgets/slides.tree.js');
	        
	        $this->addJavascript($this->digitalsignage->config['js_url'].'mgr/widgets/slides.types.grid.js');
	
	        $this->addJavascript($this->digitalsignage->config['assets_url'].'libs/extensible/js/extensible-all.js');
	
	        $this->addLastJavascript($this->digitalsignage->config['js_url'].'mgr/sections/home.js');
	    }
	
	    /**
	     * @acces public.
	     * @return String.
	     */
	    public function getPageTitle() {
	        return $this->modx->lexicon('digitalsignage');
	    }
	
	    /**
	    * @acces public.
	    * @return String.
	    */
	    public function getTemplateFile() {
	        return $this->digitalsignage->config['templates_path'].'home.tpl';
	    }
	    
        /**
         * @access public.
         * @param Array $scriptProperties.
         */
        public function process(array $scriptProperties = array()) {
            if ($this->modx->getOption('use_editor') && $richtext = $this->modx->getOption('which_editor')) {
                if ('TinyMCE RTE' == $richtext) {
                    $tinymcerte = $this->modx->getService('tinymcerte', 'TinyMCERTE', $this->modx->getOption('tinymcerte.core_path', null, $this->modx->getOption('core_path') . 'components/tinymcerte/') . 'model/tinymcerte/');

                    if ($tinymcerte instanceof TinyMCERTE) {
                        $this->addJavascript($tinymcerte->getOption('jsUrl') . 'vendor/tinymce/tinymce.min.js');
                        $this->addJavascript($tinymcerte->getOption('jsUrl') . 'vendor/autocomplete.js');
                        $this->addJavascript($tinymcerte->getOption('jsUrl') . 'mgr/tinymcerte.js');
                        }
                }

                $properties = array(
                    'editor'    => $richtext,
                    'elements'  => array()
                );

                $onRichTextEditorInit = $this->modx->invokeEvent('OnRichTextEditorInit', $properties);

                if (is_array($onRichTextEditorInit)) {
                    $onRichTextEditorInit = implode('', $onRichTextEditorInit);
                }

                $this->addHtml($onRichTextEditorInit);
            }
        }
    }

?>