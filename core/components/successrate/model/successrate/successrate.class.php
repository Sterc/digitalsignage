<?php

    /**
     * Success Rate
     *
     * Copyright 2017 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
     *
     * Success Rate is free software; you can redistribute it and/or modify it under
     * the terms of the GNU General Public License as published by the Free Software
     * Foundation; either version 2 of the License, or (at your option) any later
     * version.
     *
     * Success Rate is distributed in the hope that it will be useful, but WITHOUT ANY
     * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
     * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
     *
     * You should have received a copy of the GNU General Public License along with
     * Success Rate; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
     * Suite 330, Boston, MA 02111-1307 USA
     */

    class SuccessRate {

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

            $corePath 		= $this->modx->getOption('successrate.core_path', $config, $this->modx->getOption('core_path').'components/successrate/');
            $assetsUrl 		= $this->modx->getOption('successrate.assets_url', $config, $this->modx->getOption('assets_url').'components/successrate/');
            $assetsPath 	= $this->modx->getOption('successrate.assets_path', $config, $this->modx->getOption('assets_path').'components/successrate/');

            $this->config = array_merge(array(
                'namespace'				=> $this->modx->getOption('namespace', $config, 'successrate'),
                'lexicons'				=> array('successrate:default'),
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
                'branding'				=> (boolean) $this->modx->getOption('successrate.branding', null, true),
                'branding_url'			=> 'http://www.sterc.nl',
                'branding_help_url'		=> 'http://www.sterc.nl'
            ), $config);

            $this->modx->addPackage('successrate', $this->config['model_path']);

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
         * @access protected.
         * @param String $template.
         * @param Array $properties.
         * @param String $type.
         * @return String.
         */
        protected function getTemplate($template, $properties = array(), $type = 'CHUNK') {
            if (0 === strpos($template, '@')) {
                $type 		= substr($template, 1, strpos($template, ':') - 1);
                $template	= substr($template, strpos($template, ':') + 1, strlen($template));
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
                    $output[] = array_merge($value, array(
                        'rate' => round((int) $value['success'] / ((int) $value['total'] / 100))
                    ));
                }

                $sort = array();

                foreach ($output as $key => $value) {
                    $sort[$key] = $value['rate'];
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
                CURLOPT_URL 			=> $this->modx->getOption('successrate.api_endpoint', null, 'https://intranet.sterc.nl/stercapi/v1/modx/getcommits'),
                CURLOPT_RETURNTRANSFER	=> true,
                CURLOPT_CONNECTTIMEOUT	=> 10
            ));

            $response 	= curl_exec($curl);
            $info		= curl_getinfo($curl);

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