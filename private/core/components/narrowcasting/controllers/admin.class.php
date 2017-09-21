<?php

	/**
	 * Narrowcasting
	 *
	 * Copyright 2017 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
	 *
	 * Narrowcasting is free software; you can redistribute it and/or modify it under
	 * the terms of the GNU General Public License as published by the Free Software
	 * Foundation; either version 2 of the License, or (at your option) any later
	 * version.
	 *
	 * Narrowcasting is distributed in the hope that it will be useful, but WITHOUT ANY
	 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
	 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
	 *
	 * You should have received a copy of the GNU General Public License along with
	 * Narrowcasting; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
	 * Suite 330, Boston, MA 02111-1307 USA
	 */
	
	class NarrowcastingAdminManagerController extends NarrowcastingManagerController {
	
	    /**
	     * @access public.
	     */
	    public function loadCustomCssJs() {
	        $this->addCss($this->narrowcasting->config['css_url'].'mgr/narrowcasting.css');
	
	        $this->addJavascript($this->narrowcasting->config['js_url'].'mgr/widgets/admin.panel.js');

	        $this->addJavascript($this->narrowcasting->config['js_url'].'mgr/widgets/slides.types.grid.js');
            $this->addJavascript($this->narrowcasting->config['js_url'].'mgr/widgets/slides.types.data.grid.js');

	        $this->addLastJavascript($this->narrowcasting->config['js_url'].'mgr/sections/admin.js');
	    }
	
	    /**
	     * @acces public.
	     * @return String.
	     */
	    public function getPageTitle() {
	        return $this->modx->lexicon('narrowcasting');
	    }
	
	    /**
	    * @acces public.
	    * @return String.
	    */
	    public function getTemplateFile() {
	        return $this->narrowcasting->config['templates_path'].'admin.tpl';
	    }
	    
	    /**
		 * @access public.
		 * @param Array $scriptProperties.
		 */
		public function process(array $scriptProperties = array()) {
			if (!$this->modx->hasPermission('narrowcasting_admin')) {
				$this->modx->sendRedirect($this->modx->getOption('manager_url').'?a='.$_GET['a'].'&namespace='.$this->narrowcasting->config['namespace']);
			}
		}
	}
	
?>