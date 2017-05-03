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

	abstract class NarrowcastingManagerController extends modExtraManagerController {
		/**
		 * @access public.
		 * @var Object.
		 */
		public $narrowcasting;
		
		/**
		 * @access public.
		 * @return Mixed.
		 */
		public function initialize() {
			$this->narrowcasting = $this->modx->getService('narrowcasting', 'Narrowcasting', $this->modx->getOption('narrowcasting.core_path', null, $this->modx->getOption('core_path').'components/narrowcasting/').'model/narrowcasting/');
			
			$this->addJavascript($this->narrowcasting->config['js_url'].'mgr/narrowcasting.js');
			
			$this->addHtml('<script type="text/javascript">
				Ext.onReady(function() {
					MODx.config.help_url = "'.$this->narrowcasting->getHelpUrl().'";
			
					Narrowcasting.config = '.$this->modx->toJSON($this->narrowcasting->config).';
				});
			</script>');
			
			return parent::initialize();
		}
		
		/**
		 * @acces public.
		 * @return Array.
		 */
		public function getLanguageTopics() {
			return $this->narrowcasting->config['lexicons'];
		}
		
		/**
		 * @acces public.
		 * @returns Boolean.
		 */	    
		public function checkPermissions() {
			return true;
		}
	}
		
	class IndexManagerController extends NarrowcastingManagerController {
		/**
		 * @acces public.
		 * @return String.
		 */
		public static function getDefaultController() {
			return 'home';
		}
	}

?>