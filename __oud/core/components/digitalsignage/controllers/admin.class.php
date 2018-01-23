<?php

	class DigitalSignageAdminManagerController extends DigitalSignageManagerController {
	
	    /**
	     * @access public.
	     */
	    public function loadCustomCssJs() {
	        $this->addCss($this->digitalsignage->config['css_url'].'mgr/digitalsignage.css');
	
	        $this->addJavascript($this->digitalsignage->config['js_url'].'mgr/widgets/admin.panel.js');

	        $this->addJavascript($this->digitalsignage->config['js_url'].'mgr/widgets/slides.types.grid.js');
            $this->addJavascript($this->digitalsignage->config['js_url'].'mgr/widgets/slides.types.data.grid.js');

	        $this->addLastJavascript($this->digitalsignage->config['js_url'].'mgr/sections/admin.js');
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
	        return $this->digitalsignage->config['templates_path'].'admin.tpl';
	    }
	    
	    /**
		 * @access public.
		 * @param Array $scriptProperties.
		 */
		public function process(array $scriptProperties = array()) {
			if (!$this->modx->hasPermission('digitalsignage_admin')) {
				$this->modx->sendRedirect($this->modx->getOption('manager_url').'?a='.$_GET['a'].'&namespace='.$this->digitalsignage->config['namespace']);
			}
		}
	}
	
?>