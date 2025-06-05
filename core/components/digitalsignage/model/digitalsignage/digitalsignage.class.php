<?php

/**
 * Digital Signage
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
 */

class DigitalSignage
{
    /**
     * @access public.
     * @var modX.
     */
    public $modx;

    /**
     * @access public.
     * @var Array.
     */
    public $config = [];

    /**
     * @access public.
     * @param modX $modx.
     * @param Array $config.
     */
    public function __construct(modX &$modx, array $config = [])
    {
        $this->modx =& $modx;

        $corePath   = $this->modx->getOption('digitalsignage.core_path', $config, $this->modx->getOption('core_path') . 'components/digitalsignage/');
        $assetsUrl  = $this->modx->getOption('digitalsignage.assets_url', $config, $this->modx->getOption('assets_url') . 'components/digitalsignage/');
        $assetsPath = $this->modx->getOption('digitalsignage.assets_path', $config, $this->modx->getOption('assets_path') . 'components/digitalsignage/');

        $this->config = array_merge([
            'namespace'                 => 'digitalsignage',
            'lexicons'                  => ['digitalsignage:default', 'digitalsignage:slides', 'site:digitalsignage'],
            'base_path'                 => $corePath,
            'core_path'                 => $corePath,
            'model_path'                => $corePath . 'model/',
            'processors_path'           => $corePath . 'processors/',
            'elements_path'             => $corePath . 'elements/',
            'chunks_path'               => $corePath . 'elements/chunks/',
            'plugins_path'              => $corePath . 'elements/plugins/',
            'snippets_path'             => $corePath . 'elements/snippets/',
            'templates_path'            => $corePath . 'templates/',
            'assets_path'               => $assetsPath,
            'js_url'                    => $assetsUrl . 'js/',
            'css_url'                   => $assetsUrl . 'css/',
            'assets_url'                => $assetsUrl,
            'connector_url'             => $assetsUrl . 'connector.php',
            'version'                   => '1.2.0',
            'branding_url'              => $this->modx->getOption('digitalsignage.branding_url', null, ''),
            'branding_help_url'         => $this->modx->getOption('digitalsignage.branding_url_help', null, ''),
            'permissions'               => [
                'admin'                     => $this->modx->hasPermission('digitalsignage_admin')
            ],
            'request_id'                => $this->modx->getOption('digitalsignage.request_resource'),
            'request_url'               => '',
            'export_id'                 => $this->modx->getOption('digitalsignage.export_resource'),
            'export_url'                => '',
            'export_feed_id'            => $this->modx->getOption('digitalsignage.export_feed_resource'),
            'export_feed_url'           => '',
            'request_param_player'      => $this->modx->getOption('digitalsignage.request_param_player', null, 'pl'),
            'request_param_broadcast'   => $this->modx->getOption('digitalsignage.request_param_broadcast', null, 'bc'),
            'templates'                 => $this->getTemplates()
        ], $config);

        if (!empty($this->config['request_id'])) {
            $this->config['request_url'] = $this->modx->makeUrl($this->config['request_id'], $this->modx->getOption('digitalsignage.context', null, 'ds'), null, 'full');
        }

        if (!empty($this->config['export_id'])) {
            $this->config['export_url'] = $this->modx->makeUrl($this->config['export_id'], $this->modx->getOption('digitalsignage.context', null, 'ds'), null, 'full');
        }

        if (!empty($this->config['export_feed_id'])) {
            $this->config['export_feed_url'] = $this->modx->makeUrl($this->config['export_feed_id'], $this->modx->getOption('digitalsignage.context', null, 'ds'), null, 'full');
        }

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
    public function getHelpUrl()
    {
        if (!empty($this->config['branding_help_url'])) {
            return $this->config['branding_help_url'] . '?v=' . $this->config['version'];
        }

        return false;
    }
    /**
     * @access public.
     * @return String|Boolean.
     */
    public function getBrandingUrl()
    {
        if (!empty($this->config['branding_url'])) {
            return $this->config['branding_url'];
        }

        return false;
    }

    /**
     * @access public.
     * @param String $key.
     * @param Array $options.
     * @param Mixed $default.
     * @return Mixed.
     */
    public function getOption($key, array $options = [], $default = null)
    {
        if (isset($options[$key])) {
            return $options[$key];
        }

        if (isset($this->config[$key])) {
            return $this->config[$key];
        }

        return $this->modx->getOption($this->config['namespace'] . '.' . $key, $options, $default);
    }

    /**
     * @access public.
     * @return Array.
     */
    public function getTemplates()
    {
        $templates = explode(',', $this->modx->getOption('digitalsignage.templates'));

        foreach (array_filter($templates) as $key => $id) {
            $template = $this->modx->getObject('modTemplate', [
                'id' => $id
            ]);

           if ($template === null) {
               unset($templates[$id]);
           }
        }

        return $templates;
    }

    /**
     * @access public.
     * @param String $key.
     * @return Object|Null.
     */
    public function getPlayer($key)
    {
        return $this->modx->getObject('DigitalSignagePlayers', [
            'key' => $key
        ]);
    }

    /**
     * @access public.
     * @param Integer $id.
     * @return Object|Null.
     */
    public function getBroadcast($id)
    {
        return $this->modx->getObject('DigitalSignageBroadcasts', [
            'id' => $id
        ]);
    }

    /**
     * @access public.
     * @param Integer $id.
     * @return Object|Null.
     */
    public function getSlideType($id)
    {
        return $this->modx->getObject('DigitalSignageSlidesTypes', [
            'id' => $id
        ]);
    }

    /**
     * @access public.
     */
    public function initializeContext()
    {
        if ($this->modx->event->name === 'OnHandleRequest') {
            $this->autoPublish();
            if ($this->modx->context->get('key') !== 'mgr') {
                $base       = '/ds/';
                $context    = $this->getOption('context', [], 'ds');
                $setting    = $this->modx->getObject('modContextSetting', [
                    'context_key'   => $context,
                    'key'           => 'base_url'
                ]);

                if ($setting) {
                    $base = $setting->get('value');
                }

                if (strpos($_SERVER['REQUEST_URI'], $base) === 0) {
                    $this->modx->switchContext($context);

                    $this->modx->setOption('site_start', $this->getOption('request_resource'));
                    $this->modx->setOption('error_page', $this->getOption('request_resource'));

                    if ((int) $this->modx->getOption('friendly_urls') === 1) {
                        $alias = $this->modx->getOption('request_param_alias', null, 'q');

                        if (isset($_REQUEST[$alias])) {
                            $_REQUEST[$alias] = substr('/' . ltrim($_REQUEST[$alias], '/'), strlen($base));
                        }
                    }
                }
            }
        }
    }

    /**
     * @access public.
     */
    public function initializePlayer()
    {
        if ($this->modx->event->name === 'OnLoadWebDocument') {
            $templates = (array) $this->config['templates'];

            if (in_array($this->modx->resource->get('template'), $templates, false)) {
                $parameters = $this->getCurrentRequestParameters();
                $player     = $this->getPlayer($parameters[$this->getOption('request_param_player')]);
                $broadcast  = $this->getBroadcast($parameters[$this->getOption('request_param_broadcast')]);

                if ($player && $broadcast) {
                    $this->modx->toPlaceholders([
                        'hash'          => time(),
                        'player'        => [
                            'id'            => $player->get('id'),
                            'key'           => $player->get('key'),
                            'resolution'    => $player->get('resolution'),
                            'mode'          => $player->getMode()
                        ],
                        'broadcast'     => [
                            'id'            => $broadcast->get('id'),
                            'feed'          => $this->getOption('export_url'),
                        ],
                        'callback'      => [
                            'feed'          => $this->getOption('request_url')
                        ],
                        'feed'          => [
                            'feed'          => $this->getOption('export_feed_url')
                        ],
                        'preview'       => isset($parameters['preview']) ? 1 : 0,
                        'slide'         => !empty($parameters['slide']) ? (int)$parameters['slide'] : 0
                    ], 'digitalsignage');
                }
            }
        }

        if ($this->modx->event->name === 'OnWebPagePrerender') {
            if ((int) $this->modx->resource->get('id') === (int) $this->getOption('request_id')) {
                $parameters = $this->getCurrentRequestParameters();

                $status = [];

                if (!$this->getCurrentRequest('preview')) {
                    $player = $this->getPlayer($parameters[$this->getOption('request_param_player')]);

                    if ($player) {
                        $schedules = [];

                        foreach ((array) $player->getBroadcasts() as $broadcast) {
                            $schedule = $broadcast->isScheduled($player->get('id'));

                            if ($schedule) {
                                $broadcast = $schedule->getBroadcast();

                                if ($broadcast) {
                                    $schedules[] = array_merge($schedule->toArray(), [
                                        'broadcast' => $broadcast->toArray()
                                    ]);
                                }
                            }
                        }

                        $sort = [];

                        foreach ($schedules as $key => $value) {
                            $sort[$key] = $value['type'];
                        }

                        array_multisort($sort, SORT_ASC, $schedules);

                        if (count($schedules) >= 1) {
                            $schedule = array_shift($schedules);

                            if (!isset($parameters['data'])) {
                                $this->modx->sendRedirect($this->modx->makeUrl($schedule['broadcast']['resource_id'], null, [
                                    $this->getOption('request_param_player')       => $player->get('key'),
                                    $this->getOption('request_param_broadcast')    => $schedule['broadcast']['id']
                                ], 'full'));
                            }

                            $status = [
                                'status'    => 200,
                                'player'    => array_merge($player->toArray(), [
                                    'restart'   => $player->setOnline($parameters['time'], $schedule['broadcast']['id'])
                                ]),
                                'schedule'  => $schedule,
                                'broadcast' => $schedule['broadcast'],
                                'redirect'  => str_replace('&amp;', '&', $this->modx->makeUrl($schedule['broadcast']['resource_id'], null, [
                                    $this->getOption('request_param_player')       => $player->get('key'),
                                    $this->getOption('request_param_broadcast')    => $schedule['broadcast']['id']
                                ], 'full'))
                            ];
                        } else {
                            $status = [
                                'status'    => 400,
                                'message'   => 'No broadcast available for the player with the key `' . $parameters[$this->getOption('request_param_player')] . '`.'
                            ];
                        }
                    } else {
                        $status = [
                            'status'    => 400,
                            'message'   => 'No player found with the key `' . $parameters[$this->getOption('request_param_player')] . '`.'
                        ];
                    }
                } else {
                    $status = [
                        'status' => 200
                    ];
                }

                $this->modx->resource->_output = json_encode($status);
            }
        }
    }

    /**
     * @access public.
     * @return String.
     */
    public function initializeBroadcast()
    {
        $status     = [];
        $broadcast  = null;

        $parameters = $this->getCurrentRequestParameters();

        if (!$this->getCurrentRequest('preview')) {
            $player = $this->getPlayer($parameters[$this->getOption('request_param_player')]);

            if ($player) {
                $broadcast = $player->getCurrentBroadcast();
            }
        } else {
            $broadcast = $this->getBroadcast($parameters[$this->getOption('request_param_broadcast')]);
        }

        if ($broadcast) {
            if ($this->getCurrentRequest('ticker')) {
                $items = [];

                foreach ((array) $broadcast->getTickerItems() as $item) {
                    $items[] = [
                        'title'     => $item['title'],
                        'source'    => $item['source']
                    ];
                }

                $status = [
                    'items' => $items
                ];
            } else {
                $slides = [];

                $mediaSourceUrl = $this->getMediaSourceUrl();

                if (!isset($parameters['preview'])) {
                    $slides = $broadcast->fromExport();
                }

                if (count($slides) === 0) {
                    if (!empty($parameters['slide'])) {
                        $id = (int)$parameters['slide'];
                        if ($slide = $this->modx->getObject('DigitalSignageSlides', $id)) {
                            if ($slide->getOne('getSlideType')) {
                                $slides[] = array_merge([
                                    'id'        => $slide->get('id'),
                                    'time'      => $slide->get('time'),
                                    'slide'     => $slide->getOne('getSlideType')->get('key'),
                                    'source'    => 'intern',
                                    'title'     => $slide->get('name'),
                                    'image'     => null,
                                    'slideTypeId' => $slide->get('type'),
                                ], unserialize($slide->get('data')));
                            }
                        }
                    } else {
                        foreach ((array) $broadcast->getSlides() as $key => $slide) {
                            $slides[] = array_merge([
                                'id'        => $slide->get('id'),
                                'time'      => $slide->get('time'),
                                'slide'     => $slide->getOne('getSlideType')->get('key'),
                                'source'    => 'intern',
                                'title'     => $slide->get('name'),
                                'image'     => null,
                                'slideTypeId' => $slide->get('type'),
                            ], unserialize($slide->get('data')));
                        }
    
                        if ((bool) $this->getOption('auto_create_sync', [], false)) {
                            $broadcast->toExport($slides);
                        }
                    }
                }

                foreach ($slides as $key => $value) {
                    if ($slideType = $this->getSlideType($value['slideTypeId'])) {
                        $fieldData = unserialize($slideType->get('data'));
    
                        foreach ((array) $value as $subKey => $subValue) {
                            if (
                                array_key_exists($subKey, $fieldData) &&
                                $fieldData[$subKey]['xtype'] === 'modx-combo-browser' &&
                                stripos($subKey, 'extern') === false
                            ) {
                                $slides[$key][$subKey] = $mediaSourceUrl . $subValue;
                            }
                        }
                    }
                }

                foreach ((array) $broadcast->getFeeds('content') as $key => $feed) {
                    foreach ((array) $feed->getSlides() as $slide) {
                        $slides[] = array_merge($slide, [
                            'time'      => $feed->get('time'),
                            'slide'     => $feed->get('key'),
                            'source'    => $feed->get('key'),
                        ]);
                    }
                }

                $total = count($slides);

                foreach ((array) $broadcast->getFeeds('specials') as $key => $feed) {
                    foreach ((array) $feed->getSlides() as $slice => $slide) {
                        if ($slice < ceil($total / $feed->get('frequency'))) {
                            array_splice($slides, (($slice + 1) * $feed->get('frequency')) + $key + $slice, 0, [array_merge($slide, [
                                'time'      => $feed->get('time'),
                                'slide'     => $feed->get('key'),
                                'source'    => $feed->get('key'),
                            ])]);
                        }
                    }
                }

                $status = [
                    'slides' => $slides
                ];
            }
        }

        return json_encode($status);
    }

    /**
     * @access public.
     * @param Null|String $request.
     * @return String|Boolean.
     */
    public function getCurrentRequest($request = null)
    {
        $parameters = $this->getCurrentRequestParameters();

        if ($request !== null) {
            if ($request === 'preview') {
                return isset($parameters['preview']);
            }

            return $request === $parameters['type'];
        }

        return $parameters['type'];
    }

    /**
     * @access public.
     * @return Array.
     */
    public function getCurrentRequestParameters()
    {
        $parameters = $this->modx->request->getParameters();

        return array_merge([
            'type'                                           => null,
            'time'                                           => 900,
            $this->getOption('request_param_player')    => null,
            $this->getOption('request_param_broadcast') => null
        ], $parameters);
    }

    /**
     * @access protected,
     * @return String.
     */
    protected function getMediaSourceUrl()
    {

        $mediaSourceId = (int)$this->modx->getOption('digitalsignage.media_source');
        $version = $this->modx->getVersionData()['full_version'];

        if (version_compare($version, '3.0.0', '>=')) {
            // MODX 3+
            $mediaSource = $this->modx->getObject(\MODX\Revolution\Sources\modMediaSource::class, [
                'id' => $mediaSourceId
            ]);
        } else {
            // MODX 2.x
            $mediaSource = $this->modx->getObject('sources.modMediaSource', [
                'id' => $mediaSourceId
            ]);
        }

        if ($mediaSource) {
            $mediaSource = $mediaSource->get('properties');

            if (isset($mediaSource['baseUrl']['value'])) {
                return '/' . ltrim($mediaSource['baseUrl']['value'], '/');
            }
        }

        return '/';
    }

    /**
     * Check for and process Slides with pub_date or unpub_date set to now or in past.
     */
    public function autoPublish() {
        $now           = time();
        $table         = $this->modx->getTableName('DigitalSignageSlides');
        $cache_key     = 'auto_publish';
        $cache_options = [xPDO::OPT_CACHE_KEY => 'digitalsignage'];

        $nextevent = $this->modx->cacheManager->get($cache_key, $cache_options);
        if ($nextevent && $nextevent > $now) {
            return;
        }

        /* publish and unpublish slides using pub_date and unpub_date checks */
        $this->modx->exec("UPDATE {$table} SET published=1, pub_date=0 WHERE pub_date IS NOT NULL AND pub_date < {$now} AND pub_date > 0");
        $this->modx->exec("UPDATE {$table} SET published=0, pub_date=0, unpub_date=0 WHERE unpub_date IS NOT NULL AND unpub_date < {$now} AND unpub_date > 0");

        /* update publish time file */
        $times = [];

        $sql  = "SELECT MIN(pub_date) FROM {$table} WHERE published = 0 AND pub_date > ?";
        $stmt = $this->modx->prepare($sql);
        if ($stmt) {
            $stmt->bindValue(1, 0);
            if ($stmt->execute()) {
                foreach ($stmt->fetchAll(PDO::FETCH_NUM) as $value) {
                    if ($value[0]) {
                        $times[] = $value[0];
                        unset($value);
                        break;
                    }
                }
            }
        }

        $sql  = "SELECT MIN(unpub_date) FROM {$table} WHERE published = 1 AND unpub_date > ?";
        $stmt = $this->modx->prepare($sql);
        if ($stmt) {
            $stmt->bindValue(1, 0);
            if ($stmt->execute()) {
                foreach ($stmt->fetchAll(PDO::FETCH_NUM) as $value) {
                    if ($value[0]) {
                        $times[] = $value[0];
                        unset($value);
                        break;
                    }
                }
            }
        }

        if (count($times) > 0) {
            $nextevent = min($times);
        } else {
            $nextevent = 0;
        }

        /* cache the time of the next auto_publish event */
        $this->modx->cacheManager->set($cache_key, $nextevent, 0, $cache_options);
    }
}
