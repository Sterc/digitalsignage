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
	    public function __construct(modX &$modx, array $config = array()) {
	        $this->modx =& $modx;

	        $corePath 		= $this->modx->getOption('narrowcasting.core_path', $config, $this->modx->getOption('core_path').'components/narrowcasting/');
	        $assetsUrl 		= $this->modx->getOption('narrowcasting.assets_url', $config, $this->modx->getOption('assets_url').'components/narrowcasting/');
	        $assetsPath 	= $this->modx->getOption('narrowcasting.assets_path', $config, $this->modx->getOption('assets_path').'components/narrowcasting/');

	        $this->config = array_merge(array(
	            'namespace'				=> $this->modx->getOption('namespace', $config, 'narrowcasting'),
	            'lexicons'				=> array('narrowcasting:default', 'narrowcasting:slides'),
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
	            'has_permission'		=> $this->hasPermission(),
	            'request_id'			=> $this->modx->getOption('narrowcasting.request_resource'),
	            'request_url'			=> $this->modx->makeUrl($this->modx->getOption('narrowcasting.request_resource'), null, null, 'full'),
	            'export_id'				=> $this->modx->getOption('narrowcasting.export_resource'),
	            'export_url'			=> $this->modx->makeUrl($this->modx->getOption('narrowcasting.export_resource'), null, null, 'full'),
	            'request_param_player'	=> $this->modx->getOption('narrowcasting.request_param_player', null, 'pl'),
	            'request_param_broadcast' => $this->modx->getOption('narrowcasting.request_param_broadcast', null, 'bc'),
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
		 * @return Boolean.
		 */
		public function hasPermission() {
			return $this->modx->hasPermission('narrowcasting_admin');
		}

	    /**
	     * @access public.
	     * @param String $key.
	     * @return Null|Object.
	     */
	    public function getPlayer($key) {
	        return $this->modx->getObject('NarrowcastingPlayers', array(
	            'key' => $key
	        ));
	    }

	    /**
	     * @access public.
	     * @param String $id.
	     * @return Null|Object.
	     */
	    public function getBroadcast($id) {
		    return $this->modx->getObject('NarrowcastingBroadcasts', array(
	            'id' => $id
	        ));
	    }

	    /**
	     * @access public.
	     * @param Array $scriptProperties.
	     */
	    public function initializePlayer($scriptProperties = array()) {
	        if (in_array($this->modx->resource->template, $this->config['templates'])) {
		        $parameters = $this->getCurrentRequestParameters();

				if (null !== ($player = $this->getPlayer($parameters[$this->config['request_param_player']]))) {
					if (null !== ($broadcast = $this->getBroadcast($parameters[$this->config['request_param_broadcast']]))) {
			            $this->modx->toPlaceholders(array(
			                'player'	=> array(
			                	'id'			=> $player->id,
			                	'key'			=> $player->key,
			                	'resolution'	=> $player->resolution,
			                	'mode'			=> $player->getMode()
			                ),
			                'broadcast'	=> array(
				            	'id'			=> $broadcast->id,
				            	'feed'			=> $this->config['export_url'],
			                ),
			                'preview'			=> isset($parameters['preview']) ? 1 : 0
			            ), 'narrowcasting');
			        }
			    }
	        }

	        if ($this->modx->resource->id == $this->config['request_id']) {
		    	$parameters = $this->getCurrentRequestParameters();;

				$status = array();

				if ($this->getCurrentRequest('ticker')) {
					// TODO: test this
				} else {
					if (!$this->getCurrentRequest('preview')) {
				        if (null !== ($player = $this->getPlayer($parameters[$this->config['request_param_player']]))) {
					        $schedules = array();

					        foreach ($player->getBroadcasts() as $broadcast) {
						    	if (false !== ($schedule = $broadcast->isScheduled($player->id))) {
						    		if (null !== ($broadcast = $schedule->getBroadcast())) {
										$schedules[] = array_merge($schedule->toArray(), array(
											'broadcast'	=> $broadcast->toArray()
										));
						    		}
						    	}
					        }

					        // Sort the available schedules by type (dates overules day)
					        $sort = array();

							foreach ($schedules as $key => $value) {
							    $sort[$key] = $value['type'];
							}

							array_multisort($sort, SORT_ASC, $schedules);

					        if (0 < count($schedules)) {
						        // Get the first available schedule
								$schedule = array_shift($schedules);

						        $player->setOnline($schedule['broadcast']['id']);

						        if (!isset($parameters['data'])) {
							        $this->modx->sendRedirect($this->modx->makeUrl($schedule['broadcast']['resource_id'], null, array(
							        	$this->config['request_param_player']		=> $player->key,
							        	$this->config['request_param_broadcast']	=> $schedule['broadcast']['id']
						        	), 'full'));
						        }

						        $status = array(
						        	'status'	=> 200,
						        	'player'	=> $player->toArray(),
						        	'schedule'	=> $schedule,
						        	'broadcast'	=> $schedule['broadcast'],
						        	'redirect'	=> str_replace('&amp;', '&', $this->modx->makeUrl($schedule['broadcast']['resource_id'], null, array(
							        	$this->config['request_param_player']		=> $player->key,
							        	$this->config['request_param_broadcast']	=> $schedule['broadcast']['id']
						        	), 'full'))
						        );
						    } else {
							    $status = array(
								    'status'	=> 400,
								    'message'	=> 'Geen uitzending voor de player gevonden.'
							    );
						    }
				        } else {
					        $status = array(
						    	'status'	=> 400,
						    	'message'	=> 'Player niet gevonden.'
					        );
				        }
				    } else {
					    $status = array(
						    'status'	=> 200,
						);
				    }
				}

		        $this->modx->resource->_output = $this->modx->toJSON($status);
	        }
	    }

	    /**
	     * @access public.
	     * @param Array $scriptProperties.
	     * @return Array.
	     */
	    public function initializeBroadcast($scriptProperties = array()) {
		    $status = array();
		    $broadcast = null;

		    $parameters = $this->getCurrentRequestParameters();

			if ($this->getCurrentRequest('preview')) {
				$broadcast = $this->getBroadcast($parameters[$this->config['request_param_broadcast']]);
			} else {
				if (null !== ($player = $this->getPlayer($parameters[$this->config['request_param_player']]))) {
					$broadcast = $player->getCurrentBroadcast();
				}
			}

			if ($this->getCurrentRequest('ticker')) {
	            $items = array();

	            if (null !== $broadcast) {
		            foreach ($broadcast->getTickerItems() as $item) {
			            $items[] = array(
							'title'	=> (string) $item->title
						);
		            }
		        }

	            $status = array(
					'items' => $items
				);
            } else {
				$slides = array();

				if (null !== $broadcast) {
					if (!isset($parameters['preview'])) {
	                	$slides = $broadcast->fromExport();
	                }

	                $mediaSource    = $this->modx->getObject('modMediaSource', $this->modx->getOption('narrowcasting.media_source'));
                    $mediaSourceUrl = '';

					if ($mediaSource) {
                        $mediaSource    = $mediaSource->get('properties');
                        $mediaSourceUrl = $mediaSource['baseUrl']['value'];
					}

	                if (0 >= count($slides)) {
	                    foreach ($broadcast->getSlides() as $key => $slide) {
                            $data = unserialize($slide->data);

	                        $slides[] = array_merge(array(
	                            'time'  	=> $slide->time,
	                            'slide' 	=> $slide->type,
	                            'source'	=> 'intern',
	                            'title' 	=> $slide->name,
	                            'image' 	=> null
	                        ), $data);
	                    }

	                    if ((bool) $this->modx->getOption('narrowcasting.auto_create_sync', null, false)) {
	                    	$broadcast->toExport($slides);
	                    }
	                }

	                foreach ($slides as $key => $value) {
					    if (isset($value['image']) && $value['image'] !== '') {
					        $slides[$key]['image'] = $mediaSourceUrl . $value['image'];
                        }
                    }

	                $total = count($slides);

	                foreach ($broadcast->getFeeds() as $key => $feed) {
	                    foreach ($feed->getSlides() as $key2 => $slide) {
	                        // TODO: test this
	                        if ($key2 < ceil($total / $feed->frequency)) {
		                        $value = array(
			                        'time'		=> $feed->time,
			                        'slide'		=> 'default',
			                        'source'	=> $feed->key,
			                        'title'		=> (string) $slide->title,
			                        'image'		=> null,
			                        'content'	=> (string) $slide->description
		                        );

		                        if (isset($slide->enclosure->attributes()->url)) {
			                    	$value['image'] = (string) $slide->enclosure->attributes()->url;
			                    }

	                        	array_splice($slides, (($key2 + 1) * $feed->frequency) + $key + $key2, 0, array($value));
	                        }
	                    }
	                }
	            }

				$status = array(
					'slides' => $slides
				);
			}

			return $this->modx->toJSON($status);
		}

		/**
		 * @access public.
		 * @param Null|String $request.
		 * @return Boolean|String.
		 */
		public function getCurrentRequest($request = null) {
			$parameters = $this->getCurrentRequestParameters();

			if ('preview' == $request) {
				return isset($parameters['preview']);
			}

			if (null !== $request) {
				return $request == $parameters['type'];
			}

			return $parameters['type'];
		}

		/**
		 * @access public.
		 * @return Array.
		 */
		public function getCurrentRequestParameters() {
			return array_merge(array(
				'type'										=> null,
	        	$this->config['request_param_player'] 		=> null,
	        	$this->config['request_param_broadcast']	=> null
	        ), $this->modx->request->getParameters());
		}
	}

?>
