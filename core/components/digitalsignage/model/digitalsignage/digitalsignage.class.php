<?php

    class DigitalSignage {

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

            $corePath       = $this->modx->getOption('digitalsignage.core_path', $config, $this->modx->getOption('core_path').'components/digitalsignage/');
            $assetsUrl      = $this->modx->getOption('digitalsignage.assets_url', $config, $this->modx->getOption('assets_url').'components/digitalsignage/');
            $assetsPath     = $this->modx->getOption('digitalsignage.assets_path', $config, $this->modx->getOption('assets_path').'components/digitalsignage/');

            $this->config = array_merge(array(
                'namespace'             => $this->modx->getOption('namespace', $config, 'digitalsignage'),
                'lexicons'              => array('digitalsignage:default', 'digitalsignage:slides', 'site:digitalsignage'),
                'base_path'             => $corePath,
                'core_path'             => $corePath,
                'model_path'            => $corePath.'model/',
                'processors_path'       => $corePath.'processors/',
                'elements_path'         => $corePath.'elements/',
                'chunks_path'           => $corePath.'elements/chunks/',
                'cronjobs_path'         => $corePath.'elements/cronjobs/',
                'plugins_path'          => $corePath.'elements/plugins/',
                'snippets_path'         => $corePath.'elements/snippets/',
                'templates_path'        => $corePath.'templates/',
                'assets_path'           => $assetsPath,
                'js_url'                => $assetsUrl.'js/',
                'css_url'               => $assetsUrl.'css/',
                'assets_url'            => $assetsUrl,
                'connector_url'         => $assetsUrl.'connector.php',
                'version'               => '1.1.3',
                'branding_url'          => $this->modx->getOption('digitalsignage.branding_url', null, ''),
                'branding_help_url'     => $this->modx->getOption('digitalsignage.branding_url_help', null, ''),
                'has_permission'        => $this->hasPermission(),
                'request_id'            => $this->modx->getOption('digitalsignage.request_resource'),
                'request_url'           => $this->modx->makeUrl($this->modx->getOption('digitalsignage.request_resource'), $this->modx->getOption('digitalsignage.context', null, 'nc'), null, 'full'),
                'export_id'             => $this->modx->getOption('digitalsignage.export_resource'),
                'export_url'            => $this->modx->makeUrl($this->modx->getOption('digitalsignage.export_resource'), $this->modx->getOption('digitalsignage.context', null, 'nc'), null, 'full'),
                'request_param_player'  => $this->modx->getOption('digitalsignage.request_param_player', null, 'pl'),
                'request_param_broadcast' => $this->modx->getOption('digitalsignage.request_param_broadcast', null, 'bc'),
                'templates'             => $this->getTemplates()
            ), $config);

            $this->modx->addPackage('digitalsignage', $this->config['model_path']);

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
         * @return String|Boolean.
         */
        public function getHelpUrl() {
            if (!empty($this->config['branding_help_url'])) {
                return $this->config['branding_help_url'].'?v=' . $this->config['version'];
            }
            return false;
        }
        /**
         * @access public.
         * @return String|Boolean.
         */
        public function getBrandingUrl() {
            if (!empty($this->config['branding_url'])) {
                return $this->config['branding_url'];
            }
            return false;
        }

        /**
         * @access public.
         * @return Boolean.
         */
        public function hasPermission() {
            return $this->modx->hasPermission('digitalsignage_admin');
        }

        /**
         * @access public.
         * @return Array.
         */
        public function getTemplates() {
            $templates = array_filter(explode(',', $this->modx->getOption('digitalsignage.templates')));

            foreach ($templates as $key => $template) {
               $c = array(
                   'id' => $template
               );

               if (null === $this->modx->getObject('modTemplate', $c)) {
                   unset($templates[$key]);
               }
            }

            return $templates;
        }

        /**
         * @access public.
         * @param String $key.
         * @return Null|Object.
         */
        public function getPlayer($key) {
            return $this->modx->getObject('DigitalSignagePlayers', array(
                'key' => $key
            ));
        }

        /**
         * @access public.
         * @param String $id.
         * @return Null|Object.
         */
        public function getBroadcast($id) {
            return $this->modx->getObject('DigitalSignageBroadcasts', array(
                'id' => $id
            ));
        }

        /**
         * @access public.
         * @param Array $scriptProperties.
         */
        public function initializeContext($scriptProperties = array()) {
            if ('OnHandleRequest' == $this->modx->event->name) {
                if ('mgr' != $this->modx->context->key) {
                    $key = $this->modx->getOption('digitalsignage.context', null, 'dc');
                    $base = '/dc/';

                    $c = array(
                        'context_key' => $key,
                        'key' => 'base_url'
                    );

                    if (null !== ($object = $this->modx->getObject('modContextSetting', $c))) {
                        $base = $object->value;
                    }

                    if (0 === strpos($_SERVER['REQUEST_URI'], $base)) {
                        $this->modx->switchContext($key);
                        $this->modx->setOption('site_start', $this->modx->getOption('digitalsignage.request_resource'));
                        $this->modx->setOption('error_page', $this->modx->getOption('digitalsignage.request_resource'));

                        if (1 == $this->modx->getOption('friendly_urls')) {
                            $alias = $this->modx->getOption('request_param_alias', null, 'q');

                            if (isset($_REQUEST[$alias])) {
                                $_REQUEST[$alias] = substr('/'.ltrim($_REQUEST[$alias], '/'), strlen($base));
                            }
                        }
                    }
                }
            }
        }

        /**
         * @access public.
         * @param Array $scriptProperties.
         */
        public function initializePlayer($scriptProperties = array()) {
            if ('OnLoadWebDocument' == $this->modx->event->name) {
                if (in_array($this->modx->resource->template, $this->config['templates'])) {
                    $parameters = $this->getCurrentRequestParameters();

                    if (null !== ($player = $this->getPlayer($parameters[$this->config['request_param_player']]))) {
                        if (null !== ($broadcast = $this->getBroadcast($parameters[$this->config['request_param_broadcast']]))) {
                            $this->modx->toPlaceholders(array(
                                'hash'		=> time(),
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
                                'callback'	=> array(
                                    'feed'			=> $this->config['request_url']
                                ),
                                'preview'			=> isset($parameters['preview']) ? 1 : 0
                            ), 'digitalsignage');
                        }
                    }
                }
            }

            if ('OnWebPagePrerender' == $this->modx->event->name) {
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

                                    if (!isset($parameters['data'])) {
                                        $this->modx->sendRedirect($this->modx->makeUrl($schedule['broadcast']['resource_id'], null, array(
                                            $this->config['request_param_player']		=> $player->key,
                                            $this->config['request_param_broadcast']	=> $schedule['broadcast']['id']
                                        ), 'full'));
                                    }

                                    $status = array(
                                        'status'	=> 200,
                                        'player'	=> array_merge($player->toArray(), array(
                                            'restart'   => $player->setOnline($parameters['time'], $schedule['broadcast']['id'])
                                        )),
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
                                        'message'	=> 'No broadcast available for the player with the key \''.$parameters[$this->config['request_param_player']].'\'.'
                                    );
                                }
                            } else {
                                $status = array(
                                    'status'	=> 400,
                                    'message'	=> 'No player found with the key \''.$parameters[$this->config['request_param_player']].'\'.'
                                );
                            }
                        } else {
                            $status = array(
                                'status'	=> 200
                            );
                        }
                    }

                    $this->modx->resource->_output = $this->modx->toJSON($status);
                }
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
                    $mediaSourceUrl = $this->getMediaSourceUrl();

                    if (!isset($parameters['preview'])) {
                        $slides = $broadcast->fromExport();
                    }

                    if (0 >= count($slides)) {
                        foreach ($broadcast->getSlides() as $key => $slide) {
                            $slides[] = array_merge(array(
                                'id'        => $slide->get('id'),
                                'time'      => $slide->get('time'),
                                'slide'     => $slide->get('type'),
                                'source'    => 'intern',
                                'title'     => $slide->get('name'),
                                'image'     => null
                            ), unserialize($slide->data));
                        }

                        if ((bool) $this->modx->getOption('digitalsignage.auto_create_sync', null, false)) {
                            $broadcast->toExport($slides);
                        }
                    }

                    foreach ($slides as $key => $value) {
                        if (isset($value['image']) && $value['image'] !== '') {
                            $slides[$key]['image'] = $mediaSourceUrl.$value['image'];
                        }
                    }

                    foreach ($broadcast->getFeeds('content') as $key => $feed) {
                        foreach ($feed->getSlides() as $slide) {
                            $slide = array_merge($slide, array(
                                'time'		=> $feed->time,
                                'slide'		=> $feed->key,
                                'source'	=> $feed->key,
                            ));

                            $slides[] = $slide;
                        }
                    }

                    $total = count($slides);

                    foreach ($broadcast->getFeeds('specials') as $key => $feed) {
                        foreach ($feed->getSlides() as $slice => $slide) {
                            if ($slice < ceil($total / $feed->frequency)) {
                                $slide = array_merge($slide, array(
                                    'time'		=> $feed->time,
                                    'slide'		=> $feed->key,
                                    'source'	=> $feed->key,
                                ));

                                array_splice($slides, (($slice + 1) * $feed->frequency) + $key + $slice, 0, array($slide));
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
                $this->config['request_param_broadcast']	=> null,
                'time'										=> 900
            ), $this->modx->request->getParameters());
        }

        /**
         * @access protected,
         * @return String.
         */
        protected function getMediaSourceUrl() {
            $criterea = array(
                'id' => $this->modx->getOption('digitalsignage.media_source')
            );

            if (null !== ($mediaSource = $this->modx->getObject('modMediaSource', $criterea))) {
                $mediaSource = $mediaSource->get('properties');

                if (isset($mediaSource['baseUrl']['value'])) {
                    return $mediaSource['baseUrl']['value'];
                }
            }

            return '';
        }
    }

?>
