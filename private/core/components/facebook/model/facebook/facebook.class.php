<?php

    /**
     * Facebook
     *
     * Copyright 2017 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
     *
     * Facebook is free software; you can redistribute it and/or modify it under
     * the terms of the GNU General Public License as published by the Free Software
     * Foundation; either version 2 of the License, or (at your option) any later
     * version.
     *
     * Facebook is distributed in the hope that it will be useful, but WITHOUT ANY
     * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
     * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
     *
     * You should have received a copy of the GNU General Public License along with
     * Facebook; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
     * Suite 330, Boston, MA 02111-1307 USA
     */

    define('FACEBOOK_SDK_V4_SRC_DIR', dirname(__FILE__).'/lib/facebook/src/Facebook/');

    use Facebook\FacebookSession;
    use Facebook\FacebookRequest;

    require_once dirname(__FILE__).'/lib/facebook/autoload.php';

    class Facebook {

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

            $corePath 		= $this->modx->getOption('facebook.core_path', $config, $this->modx->getOption('core_path').'components/facebook/');
            $assetsUrl 		= $this->modx->getOption('facebook.assets_url', $config, $this->modx->getOption('assets_url').'components/facebook/');
            $assetsPath 	= $this->modx->getOption('facebook.assets_path', $config, $this->modx->getOption('assets_path').'components/facebook/');

            $this->config = array_merge(array(
                'namespace'				=> $this->modx->getOption('namespace', $config, 'facebook'),
                'lexicons'				=> array('facebook:default'),
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
                'branding'				=> (boolean) $this->modx->getOption('facebook.branding', null, true),
                'branding_url'			=> 'http://www.sterc.nl',
                'branding_help_url'		=> 'http://www.sterc.nl'
            ), $config);

            $this->modx->addPackage('facebook', $this->config['model_path']);

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
         * @param Array $scriptProperties.
         * @return Array.
         */
        public function run($scriptProperties = array()) {
            $output = array();

            $facebookAppId     = $this->modx->getOption('facebook.app_id');
            $facebookAppSecret = $this->modx->getOption('facebook.app_secret');

            if (!empty($facebookAppId) && !empty($facebookAppSecret)) {
                FacebookSession::setDefaultApplication($facebookAppId, $facebookAppSecret);

                $session = FacebookSession::newAppSession();

                try {
                    $session->validate();

                    $request = new FacebookRequest($session, 'GET', '/'.$this->modx->getOption('facebook.page').'/posts?fields=created_time,link,message,object_id,full_picture,status_type,type,from&limit=5');

                    $response    = $request->execute();
                    $graphObject = $response->getGraphObject();

                    foreach ($graphObject->getProperty('data')->asArray() as $key => $value) {
                        $output[] = array(
                            'name'      => $value->from->name,
                            'image'     => $value->full_picture,
                            'added'     => $value->created_time,
                            'content'   => $value->message,
                            'link'      => $value->link
                        );
                    }
                } catch (FacebookRequestException $ex) {
                    $output = array('Facebook exception');
                } catch (\Exception $ex) {
                    $output = array('Facebook exception');
                }
            }

            return $this->modx->toJSON(array(
                'items' => $output
           ));
        }
    }

?>
