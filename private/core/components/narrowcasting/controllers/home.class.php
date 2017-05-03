<?php

	/**
	 * Narrowcasting
	 *
	 * Copyright 2016 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
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

	class NarrowcastingHomeManagerController extends NarrowcastingManagerController {		
		/**
		 * @access public.
		 */
		public function loadCustomCssJs() {
			$this->addCss($this->narrowcasting->config['css_url'].'mgr/narrowcasting.css');
			$this->addCss($this->narrowcasting->config['assets_url'].'libs/extensible/css/extensible-all.css');
			$this->addCss($this->narrowcasting->config['assets_url'].'libs/extensible/css/extensible-default.css');
			
			$this->addJavascript($this->narrowcasting->config['js_url'].'mgr/widgets/home.panel.js');
			$this->addJavascript($this->narrowcasting->config['assets_url'].'libs/extensible/js/extensible-all.js');
			
			$this->addJavascript($this->narrowcasting->config['js_url'].'mgr/widgets/broadcasts.grid.js');
			$this->addJavascript($this->narrowcasting->config['js_url'].'mgr/widgets/broadcasts.players.grid.js');
			
			$this->addJavascript($this->narrowcasting->config['js_url'].'mgr/widgets/players.grid.js');
			$this->addJavascript($this->narrowcasting->config['js_url'].'mgr/widgets/players.schedules.grid.js');
			
			$this->addLastJavascript($this->narrowcasting->config['js_url'].'mgr/sections/home.js');
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
			return $this->narrowcasting->config['templates_path'].'home.tpl';
		}
	}

?>