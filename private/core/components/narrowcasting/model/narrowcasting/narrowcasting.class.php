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

	class Narrowcasting {
		/**
		 * @access public.
		 * @var Object.
		 */
		public $modx;
		
		/**
		 * @access public.
		 * @var Array.
		 */
		public $config = array();
		
		/**
		 * @access public.
		 * @param Object $modx.
		 * @param Array $config.
		*/
		function __construct(modX &$modx, array $config = array()) {
			$this->modx =& $modx;
		
			$corePath 		= $this->modx->getOption('narrowcasting.core_path', $config, $this->modx->getOption('core_path').'components/narrowcasting/');
			$assetsUrl 		= $this->modx->getOption('narrowcasting.assets_url', $config, $this->modx->getOption('assets_url').'components/narrowcasting/');
			$assetsPath 	= $this->modx->getOption('narrowcasting.assets_path', $config, $this->modx->getOption('assets_path').'components/narrowcasting/');
		
			$this->config = array_merge(array(
				'namespace'				=> $this->modx->getOption('namespace', $config, 'narrowcasting'),
				'lexicons'				=> array('narrowcasting:default'),
				'base_path'				=> $corePath,
				'core_path' 			=> $corePath,
				'model_path' 			=> $corePath.'model/',
				'processors_path' 		=> $corePath.'processors/',
				'elements_path' 		=> $corePath.'elements/',
				'chunks_path' 			=> $corePath.'elements/chunks/',
				'cronjobs_path' 		=> $corePath.'elements/cronjobs/',
				'plugins_path' 			=> $corePath.'elements/plugins/',
				'snippets_path' 		=> $corePath.'elements/snippets/',
				'templates_path' 		=> $corePath.'templates/',
				'assets_path' 			=> $assetsPath,
				'js_url' 				=> $assetsUrl.'js/',
				'css_url' 				=> $assetsUrl.'css/',
				'assets_url' 			=> $assetsUrl,
				'connector_url'			=> $assetsUrl.'connector.php',
				'version'				=> '1.0.0',
				'branding'				=> (boolean) $this->modx->getOption('narrowcasting.branding', null, true),
				'branding_url'			=> 'http://www.sterc.nl',
				'branding_help_url'		=> 'http://www.sterc.nl',
				'request_id'			=> $this->modx->getOption('narrowcasting.request_resource'),
				'request_url'			=> $this->modx->makeUrl($this->modx->getOption('narrowcasting.request_resource'), null, null, 'full'),
				'request_param_player'	=> $this->modx->getOption('narrowcasting.request_param_player', null, 'pl'),
				'templates'				=> explode(',', $this->modx->getOption('narrowcasting.templates'))
			), $config);
		
			$this->modx->addPackage('narrowcasting', $this->config['model_path']);
			
			if (is_array($this->config['lexicons'])) {
				foreach ($this->config['lexicons'] as $lexicon) {
					$this->modx->lexicon->load($lexicon);
				}
			} else {
				$this->modx->lexicon->load($this->config['lexicons']);
			}
		}
		
		/**
		 * @access public.
		 * @return String.
		 */
		public function getHelpUrl() {
			return $this->config['branding_help_url'].'?v='.$this->config['version'];
		}
		
		/**
		 * @access public.
		 * @param String $key.
		 * @return Null|Object.
		 */
		public function getPlayer($key) {
			$criterea = array(
                'key' => $key
            );
                    
			if (null !== ($player = $this->modx->getObject('NarrowcastingPlayers', $criterea))) {
				return $player;	
			}
			
			return null;
		}
		
		/**
		 * @access public.
		 * @return String.
		 */
		public function initializePlayer() {
			if (in_array($this->modx->resource->template, $this->config['templates'])) {
				/*$parameters = $this->modx->request->getParameters();
				
				$this->modx->setPlaceholders(array(
					'id'		=> $parameters[$this->modx->getOption('narrowcasting.request_param_broadcast')],
					'player'	=> $parameters[$this->modx->getOption('narrowcasting.request_param_player')]
				), 'broadcast.');*/
			} else if ($this->modx->resource->id == $this->config['request_id']) {
                $playerkey	= $this->config['request_param_player'];
				$parameters = $this->modx->request->getParameters();
            
                if (isset($parameters[$playerkey])) {
                    if (null !== ($player = $this->getPlayer($parameters[$playerkey]))) {
	                    $broadcasts = array();
	                    
		                foreach ($player->getBroadcasts() as $broadcast) {
			                if (false !== ($schedule = $broadcast->isScheduled())) {
				                $broadcasts[] = array_merge($broadcast->toArray(), array(
					                'schedule'	=> $schedule,
					            	'url' 		=> $this->modx->makeUrl($broadcast->resource_id, null, array(
										$playerkey => $player->key
									), 'full')
				                ));
				            }
						}

						print_r($broadcasts);
						exit();
						
						if (0 == count($broadcasts)) {
							// Er is geen uitzending bekend voor deze mediaspeler.
						} else {
							//$broadcast = array_shift($broadcast);
							
							
							//$this->modx->sendRedirect($broadcast['location']);
						}
                    } else {
	                    // Er is geen mediaspeler gedefineerd.
                    }
                } else {
	                // Er is geen mediaspeler gedefineerd.
                }
            }
		}
	}
	
?>