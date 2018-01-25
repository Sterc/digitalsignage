<?php

    class DigitalSignageBitbucket {
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

            $corePath       = $this->modx->getOption('digitalsignagebitbucket.core_path', $config, $this->modx->getOption('core_path').'components/digitalsignagebitbucket/');
            $assetsUrl      = $this->modx->getOption('digitalsignagebitbucket.assets_url', $config, $this->modx->getOption('assets_url').'components/digitalsignagebitbucket/');
            $assetsPath     = $this->modx->getOption('digitalsignagebitbucket.assets_path', $config, $this->modx->getOption('assets_path').'components/digitalsignagebitbucket/');

            $this->config = array_merge(array(
                'namespace'     => $this->modx->getOption('namespace', $config, 'digitalsignagebitbucket'),
                'lexicons'      => array('digitalsignagebitbucket:default'),
                'base_path'     => $corePath,
                'core_path'     => $corePath,
                'model_path'    => $corePath.'model/'
            ), $config);

            $this->modx->addPackage('digitalsignagebitbucket', $this->config['model_path']);

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
                'toJson'    => false,
                'sort'      => 'DESC',
                'limit'     => 10
            ), $scriptProperties);

            foreach ($this->modx->request->getParameters() as $key => $value) {
                if (isset($scriptProperties[$key])) {
                    $scriptProperties[$key] = $value;
                }
            }

            $output = array();

            if (false !== ($data = $this->getApiData())) {
                foreach ($data as $key => $value) {
                    $output[] = array_merge(array(
                        'name'      => '',
                        'total'     => 0,
                        'success'   => 0,
                        'failed'    => 0,
                        'score'     => 0
                    ), $value);
                }

                $sort = array();

                foreach ($output as $key => $value) {
                    $sort[$key] = $value['score'];
                }

                if ('DESC' == strtoupper($scriptProperties['sort'])) {
                    array_multisort($sort, SORT_DESC, $output);
                } else {
                    array_multisort($sort, SORT_ASC, $output);
                }

                if ((int) $scriptPriperties['limit'] < count($output)) {
                    $output = array_slice($output, 0, (int) $scriptProperties['limit']);
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

        /**
         * @access protected.
         * @return Boolean|String.
         */
        protected function getApiData() {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL             => $this->modx->getOption('digitalsignagebitbucket.api_endpoint', null, 'https://intranet.sterc.nl/stercapi/v1/modx/getcommits'),
                CURLOPT_RETURNTRANSFER  => true,
                CURLOPT_CONNECTTIMEOUT  => 10,
                CURLOPT_TIMEOUT         => 10,
                CURLOPT_FOLLOWLOCATION  => true
            ));

            $response   = curl_exec($curl);
            $info       = curl_getinfo($curl);

            if (!isset($info['http_code']) || '200' != $info['http_code']) {
                return false;
            }

            curl_close($curl);

            if (null !== ($data = $this->modx->fromJSON($response))) {
                if (isset($data['data'])) {
                    return $data['data'];
                }
            }

            return false;
        }
    }

?>