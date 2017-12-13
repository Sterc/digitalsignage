<?php

    define('FACEBOOK_SDK_V4_SRC_DIR', dirname(__FILE__).'/lib/facebook/src/Facebook/');

    use Facebook\FacebookSession;
    use Facebook\FacebookRequest;

    require_once dirname(__FILE__).'/lib/facebook/autoload.php';

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
        public $config = array();

        /**
         * @access public.
         * @param Object $modx.
          * @param Array $config.
         */
        public function __construct(modX &$modx, array $config = array()) {
            $this->modx =& $modx;

            $corePath       = $this->modx->getOption('digitalsignagesocialmedia.core_path', $config, $this->modx->getOption('core_path').'components/digitalsignagesocialmedia/');
            $assetsUrl      = $this->modx->getOption('digitalsignagesocialmedia.assets_url', $config, $this->modx->getOption('assets_url').'components/digitalsignagesocialmedia/');
            $assetsPath     = $this->modx->getOption('digitalsignagesocialmedia.assets_path', $config, $this->modx->getOption('assets_path').'components/digitalsignagesocialmedia/');

            $this->config = array_merge(array(
                'namespace'     => $this->modx->getOption('namespace', $config, 'digitalsignagesocialmedia'),
                'lexicons'      => array('digitalsignagesocialmedia:default'),
                'base_path'     => $corePath,
                'core_path'     => $corePath,
                'model_path'    => $corePath.'model/'
            ), $config);

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
        protected function getTemplate($template, $properties = array(), $type = 'CHUNK') {
            if (0 === strpos($template, '@')) {
                $type       = substr($template, 1, strpos($template, ':') - 1);
                $template   = substr($template, strpos($template, ':') + 1, strlen($template));
            }

            switch (strtoupper($type)) {
                case 'INLINE':
                    $chunk = $this->modx->newObject('modChunk', array(
                        'name' => $this->config['namespace'].uniqid()
                    ));

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
        public function run($scriptProperties = array()) {
            $scriptProperties = array_merge(array(
                'toJson'        => false,
                'imageRequired' => false,
                'page'          => $this->modx->getOption('digitalsignagesocialmedia.facebook_page'),
                'fields'        => 'id,name,from,full_picture,created_time,status_type,message',
                'limit'         => 10
            ), $scriptProperties);

            foreach ($this->modx->request->getParameters() as $key => $value) {
                if (isset($scriptProperties[$key])) {
                    $scriptProperties[$key] = $value;
                }
            }

            $output = array();

            $facebookAppId     = $this->modx->getOption('digitalsignagesocialmedia.facebook_app_id');
            $facebookAppSecret = $this->modx->getOption('digitalsignagesocialmedia.facebook_app_secret');

            if (!empty($facebookAppId) && !empty($facebookAppSecret)) {
                FacebookSession::setDefaultApplication($facebookAppId, $facebookAppSecret);

                $session = FacebookSession::newAppSession();

                try {
                    $session->validate();

                    $parameters = array(
                        'fields'    => $scriptProperties['fields'],
                        'limit'     => $scriptProperties['limit']
                    );

                    $request = new FacebookRequest($session, 'GET', '/'.$scriptProperties['page'].'/posts?'.http_build_query($parameters));

                    $response    = $request->execute();
                    $graphObject = $response->getGraphObject();

                    foreach ($graphObject->getProperty('data')->asArray() as $key => $value) {
                        if ((bool) $scriptProperties['imageRequired']) {
                            if (!isset($value->full_picture) || empty($value->full_picture)) {
                                continue;
                            }
                        }

                        if (!empty($value->message)) {
                            $value = array(
                                'name'      => '',
                                'link'      => $value->link,
                                'content'   => $value->message,
                                'image'     => $value->full_picture,
                                'pubDate'   => $value->created_time,
                                'creator'   => $value->from->name
                            );

                            if ('shared_story' == $value['status_type']) {
                                $value['title'] = $value->name;
                            }

                            if ((bool) $scriptProperties['toJson']) {
                                $output[] = $value;
                            } else {
                                if (isset($scriptProperties['tpl'])) {
                                    $output[] = $this->getTemplate($scriptProperties['tpl'], $value);
                                }
                            }
                        }
                    }
                } catch (FacebookRequestException $ex) {
                    $output = array('Facebook exception');
                } catch (\Exception $ex) {
                    $output = array('Facebook exception');
                }
            }

            if ((bool) $scriptProperties['toJson']) {
                return $this->modx->toJSON(array(
                    'items' => $output
                ));
            } else {
                if (0 < count($output)) {
                    if (isset($scriptProperties['tplWrapper'])) {
                        return $this->getTemplate($scriptProperties['tplWrapper'], array(
                            'output' => implode(PHP_EOL, $output)
                        ));
                    }
                }

                if (isset($scriptProperties['tplWrapperEmpty'])) {
                    return $this->getTemplate($scriptProperties['tplWrapperEmpty']);
                }

                return '';
            }
        }
    }

?>