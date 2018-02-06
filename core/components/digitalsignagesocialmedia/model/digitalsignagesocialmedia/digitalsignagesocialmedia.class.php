<?php

    define('FACEBOOK_SDK_V4_SRC_DIR', dirname(__FILE__) . '/lib/facebook/src/Facebook/');

    use Facebook\FacebookSession;
    use Facebook\FacebookRequest;

    require_once dirname(__FILE__) . '/lib/facebook/autoload.php';

    require_once dirname(__FILE__) . '/lib/twitter/TwitterAPIExchange.php';

    class DigitalSignageSocialMedia {
        /**
         * @access public.
         * @var Object.
         */
        public $modx;

        /**
         * @access public.
         * @var Array.
         */
        public $config = [];

        /**
         * @access public.
         * @param Object $modx.
          * @param Array $config.
         */
        public function __construct(modX &$modx, array $config = []) {
            $this->modx =& $modx;

            $corePath       = $this->modx->getOption('digitalsignagesocialmedia.core_path', $config, $this->modx->getOption('core_path').'components/digitalsignagesocialmedia/');
            $assetsUrl      = $this->modx->getOption('digitalsignagesocialmedia.assets_url', $config, $this->modx->getOption('assets_url').'components/digitalsignagesocialmedia/');
            $assetsPath     = $this->modx->getOption('digitalsignagesocialmedia.assets_path', $config, $this->modx->getOption('assets_path').'components/digitalsignagesocialmedia/');

            $this->config = array_merge([
                'namespace'     => $this->modx->getOption('namespace', $config, 'digitalsignagesocialmedia'),
                'lexicons'      => ['digitalsignagesocialmedia:default'],
                'base_path'     => $corePath,
                'core_path'     => $corePath,
                'model_path'    => $corePath.'model/'
            ], $config);

            $this->modx->addPackage('digitalsignagesocialmedia', $this->config['model_path']);

            if (is_array($this->config['lexicons'])) {
                foreach ($this->config['lexicons'] as $lexicon) {
                    $this->modx->lexicon->load($lexicon);
                }
            } else {
                $this->modx->lexicon->load($this->config['lexicons']);
            }
        }

        /**
         * @access protected.
         * @param String $template.
         * @param Array $properties.
         * @param String $type.
         * @return String.
         */
        protected function getTemplate($template, $properties = [], $type = 'CHUNK') {
            if (0 === strpos($template, '@')) {
                $type       = substr($template, 1, strpos($template, ':') - 1);
                $template   = substr($template, strpos($template, ':') + 1, strlen($template));
            }

            switch (strtoupper($type)) {
                case 'INLINE':
                    $chunk = $this->modx->newObject('modChunk', [
                        'name' => $this->config['namespace'].uniqid()
                    ]);

                    $chunk->setCacheable(false);

                    $output = $chunk->process($properties, ltrim($template));

                    break;
                case 'CHUNK':
                    $output = $this->modx->getChunk(ltrim($template), $properties);

                    break;
            }

            return $output;
        }

        /**
         * @access public.
         * @param Array $scriptProperties.
         * @return String.
         */
        public function run($scriptProperties = [])
        {
            $scriptProperties = array_merge([
                'sources'           => 'Facebook,Twitter,Instagram',
                'toJson'            => false,
                'imageRequired'     => false,
                'facebook_page'     => $this->modx->getOption('digitalsignagesocialmedia.facebook_page'),
                'facebook_fields'   => 'id,name,from,full_picture,created_time,status_type,message',
                'twitter_query'     => $this->modx->getOption('digitalsignagesocialmedia.twitter_query'),
                'instagram_query'   => $this->modx->getOption('digitalsignagesocialmedia.instagram_query'),
                'limit'             => 10
            ], $scriptProperties);

            foreach ($this->modx->request->getParameters() as $key => $value) {
                if (isset($scriptProperties[$key])) {
                    $scriptProperties[$key] = $value;
                }
            }

            $output = [];

            foreach ($this->getData($scriptProperties) as $value) {
                if ((bool) $scriptProperties['toJson']) {
                    $output[] = $value;
                } else {
                    if (isset($scriptProperties['tpl'])) {
                        $output[] = $this->getTemplate($scriptProperties['tpl'], $value);
                    }
                }
            }

            if ((bool) $scriptProperties['toJson']) {
                return $this->modx->toJSON([
                    'items' => $output
                ]);
            }

            if (0 < count($output)) {
                if (isset($scriptProperties['tplWrapper'])) {
                    return $this->getTemplate($scriptProperties['tplWrapper'], [
                        'output' => implode(PHP_EOL, $output)
                    ]);
                }
            }

            if (isset($scriptProperties['tplWrapperEmpty'])) {
                return $this->getTemplate($scriptProperties['tplWrapperEmpty']);
            }

            return '';
        }

        /**
         * @access private.
         * @param Array $scriptProperties.
         * @return Array.
         */
        private function getData($scriptProperties = [])
        {
            $data = [];
            $sources = array_filter(explode(',', $scriptProperties['sources']));

            foreach ($sources as $key => $source) {
                $method = 'get' . ucfirst($source) . 'Data';

                if (method_exists($this, $method)) {
                    $sources[$key] = [
                        'key'   => $source,
                        'data'  => $this->$method($scriptProperties)
                    ];
                }
            }

            for ($i = 0; $i < (int) $scriptProperties['limit']; $i++) {
                foreach ($sources as $value) {
                    if (isset($value['data'][$i])) {
                        if ((bool) $scriptProperties['imageRequired'] && empty($value['data'][$i]['image'])) {
                            continue;
                        }

                        $image = $value['data'][$i]['image'];

                        if (!empty($image)) {
                            $image = preg_replace('/^http(s)?:/si', '', $image);

                            if ('//' !== substr($image, 0, 2)) {
                                $image = '//'.$image;
                            }
                        }

                        $data[] = array_merge($value['data'][$i], [
                            'source'    => strtolower($value['key']),
                            'image'     => $image,
                            'content'   => preg_replace('/\s+/', ' ',preg_replace( '/[\r\n]+/', ' ', $value['data'][$i]['content'])),
                            'pubDate'   => date('Y-m-d H:i:s', strtotime($value['data'][$i]['pubDate']))
                        ]);
                    }
                }
            }

            return $data;
        }

        /**
         * @access private.
         * @param Array $scriptProperties.
         * @return Array.
         */
        private function getFacebookData($scriptProperties = [])
        {
            $data = [];

            $facebookAppId     = $this->modx->getOption('digitalsignagesocialmedia.facebook_app_id');
            $facebookAppSecret = $this->modx->getOption('digitalsignagesocialmedia.facebook_app_secret');

            if (!empty($scriptProperties['facebook_page']) && !empty($facebookAppId) && !empty($facebookAppSecret)) {
                FacebookSession::setDefaultApplication($facebookAppId, $facebookAppSecret);

                $session = FacebookSession::newAppSession();

                try {
                    $session->validate();

                    $parameters = array(
                        'fields'    => $scriptProperties['facebook_fields'],
                        'limit'     => $scriptProperties['limit']
                    );

                    $request = new FacebookRequest($session, 'GET', '/'.$scriptProperties['facebook_page'].'/posts?' . http_build_query($parameters));

                    $response    = $request->execute();
                    $graphObject = $response->getGraphObject();

                    foreach ($graphObject->getProperty('data')->asArray() as $key => $value) {
                        if (!empty($value->message)) {
                            $item = [
                                'name'      => $value->from->name,
                                'link'      => $value->link,
                                'content'   => $value->message,
                                'image'     => $value->full_picture,
                                'pubDate'   => $value->created_time,
                                'creator'   => $value->from->name
                            ];

                            if ('shared_story' == $value->status_type) {
                                $item['title'] = $value->name;
                            }

                            $data[] = $item;
                        }
                    }
                } catch (FacebookRequestException $ex) {
                    $data[] = [
                        'name'  => 'Facebook error'
                    ];
                } catch (\Exception $ex) {
                    $data[] = [
                        'name'  => 'Facebook error'
                    ];
                }
            } else {
                $data[] = [
                    'name'  => 'Facebook error'
                ];
            }

            return $data;
        }

        /**
         * @access private.
         * @param Array $scriptProperties.
         * @return Array.
         */
        private function getTwitterData($scriptProperties = [])
        {
            $data = [];

            $twitterToken       = $this->modx->getOption('digitalsignagesocialmedia.twitter_token');
            $twitterTokenSecret = $this->modx->getOption('digitalsignagesocialmedia.twitter_token_secret');
            $twitterConsKey     = $this->modx->getOption('digitalsignagesocialmedia.twitter_consumer_key');
            $twitterConsSecret  = $this->modx->getOption('digitalsignagesocialmedia.twitter_consumer_secret');

            if (!empty($scriptProperties['twitter_query']) && !empty($twitterToken) && !empty($twitterTokenSecret) && !empty($twitterConsKey) && !empty($twitterConsSecret)) {
                $twitter = new TwitterAPIExchange([
                    'oauth_access_token'        => $twitterToken,
                    'oauth_access_token_secret' => $twitterTokenSecret,
                    'consumer_key'              => $twitterConsKey,
                    'consumer_secret'           => $twitterConsSecret
                ]);

                if ('#' === substr($scriptProperties['twitter_query'], 0, 1)) {
                    $query = str_replace('#', '', $scriptProperties['twitter_query']);

                    $request = json_decode($twitter->setGetfield('?q=' . $query)->buildOauth('https://api.twitter.com/1.1/search/tweets.json', 'GET')->performRequest());

                    if (null !== $request && !isset($request->errors)) {
                        if (isset($request->statuses)) {
                            $data = $request->statuses;
                        }
                    }
                } else {
                    $query = str_replace('@', '', $scriptProperties['twitter_query']);

                    $request = json_decode($twitter->setGetfield('?screen_name=' . $query)->buildOauth('https://api.twitter.com/1.1/statuses/user_timeline.json', 'GET')->performRequest());

                    if (null !== $request && !isset($request->errors)) {
                        $data = $request;
                    }
                }
            }

            if (!empty($data)) {
                foreach ($data as $key => $value) {
                    $item = [
                        'name'      => '@' . $value->user->screen_name,
                        'link'      => '',
                        'content'   => $value->text,
                        'image'     => '',
                        'pubDate'   => $value->created_at,
                        'creator'   => '@' . $value->user->screen_name
                    ];

                    if (isset($value->entities->media)) {
                        foreach ($value->entities->media as $media) {
                            if ('photo' == $media->type) {
                                $item['image'] = $media->media_url;
                            }
                        }
                    }

                    $data[$key] = $item;
                }
            } else {
                $data[] = [
                    'name'  => 'Twitter error'
                ];
            }

            return $data;
        }

        /**
         * @access private.
         * @param Array $scriptProperties.
         * @return Array.
         */
        private function getInstagramData($scriptProperties = [])
        {
            $data = [];

            $instagramAccessToken = $this->modx->getOption('digitalsignagesocialmedia.instagram_access_token');

            if (!empty($scriptProperties['instagram_query']) && !empty($instagramAccessToken)) {
                if ('#' === substr($scriptProperties['instagram_query'], 0, 1)) {
                    $query = str_replace('#', '', $scriptProperties['instagram_query']);

                    $request = json_decode(file_get_contents('https://api.instagram.com/v1/tags/' . $query . '/media/recent?access_token=' . $instagramAccessToken));

                    if (null !== $request && !isset($request->errors)) {
                        if (isset($request->data)) {
                            $data = $request->data;
                        }
                    }
                } else {
                    $query = str_replace('@', '', $scriptProperties['instagram_query']);

                    $request = json_decode(file_get_contents('https://api.instagram.com/v1/users/' . $query . '/media/recent?access_token=' . $instagramAccessToken));

                    if (null !== $request && !isset($request->errors)) {
                        if (isset($request->data)) {
                            $data = $request->data;
                        }
                    }
                }
            }

            if (!empty($data)) {
                foreach ($data as $key => $value) {
                    $item = [
                        'name'      => '@' . $value->user->username,
                        'link'      => '',
                        'content'   => $value->caption->text,
                        'image'     => '',
                        'pubDate'   => date('Y-m-d H:i:s', $value->created_time),
                        'creator'   => '@' . $value->user->username
                    ];

                    if (isset($value->images)) {
                        foreach ($value->images as $image) {
                            $item['image'] = $image->url;
                        }
                    }

                    $data[$key] = $item;
                }
            } else {
                $data[] = [
                    'name'  => 'Instagram error'
                ];
            }

            return $data;
        }
    }

?>