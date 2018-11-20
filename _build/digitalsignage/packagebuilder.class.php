<?php

    /**
     * Package Builder
     *
     * Copyright 2017 by Oene Tjeerd de Bruin <modx@oetzie.nl>
     */

    set_time_limit(0);

    class PackageBuilder {
        /**
         * @access public.
         * @var Object.
         */
        public $modx;

        /**
         * @access public.
         * @var Array.
         */
        public $path = array(
            'core'      => '',
            'assets'    => '',
            'package'   => ''
        );

        /**
         * @access public.
         * @var Array.
         */
        public $elements = array(
            'chunks'    => 'modChunk',
            'plugins'   => 'modPlugin',
            'snippets'  => 'modSnippet',
            'templates' => 'modTemplate',
            'widgets'   => 'modDashboardWidget'
        );

        /**
         * @access public.
         * @var Array.
         */
        public $attributes = array('license', 'readme', 'changelog');

        /**
         * @access public.
         * @var Boolean.
         */
        public $encrypt = false;

        /**
         * @access public.
         * @var Null|String.
         */
        public $encryptKey = null;

        /**
         * @access public.
         * @var Null|Array.
         */
        public $package = null;

        /**
         * @access public.
         * @param String $package.
         * @param String $core.
         * @param String $assets.
         */
        public function __construct($package = null, $core = null, $assets = null, $modx = null) {
            if (null !== $package) {
                $this->path['package'] = $package;
            } else {
                $this->path['package'] = dirname(__FILE__).'/package.json';
            }

            if (null !== $core) {
                $this->path['core'] = rtrim($core, '/').'/';
            } else {
                $this->path['core'] = dirname(dirname(dirname(__FILE__))).'/core/';
            }

            if (null !== $assets) {
                $this->path['assets'] = rtrim($assets, '/').'/';
            } else {
                $this->path['assets'] = dirname(dirname(dirname(__FILE__))).'/assets/';
            }

            if ($modx === null) {
                require_once $this->path['core'].'config/' . MODX_CONFIG_KEY . '.inc.php';
                require_once $this->path['core'].'model/modx/modx.class.php';

                $modx = new modX();
                $modx->initialize('mgr');

                $modx->getService('error', 'error.modError');

                $modx->setLogLevel(modX::LOG_LEVEL_INFO);
                $modx->setLogTarget(XPDO_CLI_MODE ? 'ECHO' : 'HTML');
            }
            $this->modx = $modx;

            $this->path['root'] = dirname(dirname(__DIR__)) . '/';
        }

        /**
         * @access public.
         * @return Boolean|Array.
         */
        public function loadPackage() {
            if (file_exists($this->path['package'])) {
                if (false !== ($package = file_get_contents($this->path['package']))) {
                    if (null !== ($package = $this->modx->fromJSON($package))) {
                        return $this->package = $package;
                    } else {
                        $this->modx->log(modX::LOG_LEVEL_ERROR, 'Could not read the package file.');
                    }
                } else {
                    $this->modx->log(modX::LOG_LEVEL_ERROR, 'Could not read the package file.');
                }
            } else {
                $this->modx->log(modX::LOG_LEVEL_ERROR, 'Could not locate the package file.');
            }

            return false;
        }

        /**
         * @access public.
         * @return Boolean.
         */
        public function createPackageZip() {
            $time   = explode(' ', microtime());
            $time1  = $time[1] + $time[0];

            if ($this->loadPackage()) {
                $this->setPackageEncrypt();

                if ($this->modx->loadClass('transport.modPackageBuilder', '', false, true)) {
                    if (null !== ($builder = new modPackageBuilder($this->modx))) {
                        list($version, $release) = explode('-', $this->package['version']);

                        if (defined('PKG_BUILD_TARGET')) {
                            $builder->directory = PKG_BUILD_TARGET;
                        }
                        $builder->createPackage($this->package['namespace'], $version, $release);
                        $builder->registerNamespace($this->package['namespace'], false, true, '{core_path}components/'.$this->package['namespace'].'/', '{assets_path}components/'.$this->package['namespace'].'/');

                        if ($this->getPackageEncrypt()) {
                            require_once $this->getPath('core') . 'model/encryptedvehicle.class.php';

                            $builder->package->put(array(
                                'source'    => $this->getPath('core'),
                                'target'    => "return MODX_CORE_PATH . 'components/';",
                            ), array(
                                'vehicle_class' => 'xPDOFileVehicle',
                                'resolve'       => array(
                                    array(
                                        'type'      => 'php',
                                        'source'    => __DIR__ . '/resolvers/encryption.resolver.php',
                                    )
                                )
                            ));
                        }

                        $this->modx->log(modX::LOG_LEVEL_INFO, 'Packaging category.');

                        if (null !== ($category = $this->modx->newObject('modCategory'))) {
                            $category->fromArray(array(
                                'category' => $this->package['name']
                            ), '', true, true);

                            foreach ($this->getElements() as $key => $elements) {
                                $this->modx->log(modX::LOG_LEVEL_INFO, 'Packaging '.$key.' into category.');

                                foreach ($elements as $element) {
                                    $category->addMany($element);
                                }

                                $this->modx->log(modX::LOG_LEVEL_INFO, 'Packed '.count($elements).' '.$key.' into category.');
                            }

                            $vehicle = array(
                                xPDOTransport::UNIQUE_KEY       => 'category',
                                xPDOTransport::PRESERVE_KEYS    => false,
                                xPDOTransport::UPDATE_OBJECT    => true,
                                xPDOTransport::RELATED_OBJECTS  => true,
                                xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array(
                                    'Chunks' => array(
                                        xPDOTransport::PRESERVE_KEYS    => false,
                                        xPDOTransport::UPDATE_OBJECT    => true,
                                        xPDOTransport::UNIQUE_KEY       => 'name'
                                    ),
                                    'Plugins' => array(
                                        xPDOTransport::PRESERVE_KEYS    => false,
                                        xPDOTransport::UPDATE_OBJECT    => true,
                                        xPDOTransport::UNIQUE_KEY       => 'name'
                                    ),
                                    'PluginEvents' => array(
                                        xPDOTransport::PRESERVE_KEYS    => true,
                                        xPDOTransport::UPDATE_OBJECT    => false,
                                        xPDOTransport::UNIQUE_KEY       => array('pluginid', 'event')
                                    ),
                                    'Snippets' => array(
                                        xPDOTransport::PRESERVE_KEYS    => false,
                                        xPDOTransport::UPDATE_OBJECT    => true,
                                        xPDOTransport::UNIQUE_KEY       => 'name'
                                    ),
                                    'Templates' => array(
                                        xPDOTransport::PRESERVE_KEYS    => false,
                                        xPDOTransport::UPDATE_OBJECT    => true,
                                        xPDOTransport::UNIQUE_KEY       => 'templatename'
                                    )
                                )
                            );

                            if ($this->getPackageEncrypt()) {
                                $vehicle['vehicle_class'] = 'encryptedVehicle';
                                $vehicle[xPDOTransport::ABORT_INSTALL_ON_VEHICLE_FAIL] = true;
                            }

                            if (null !== ($vehicle = $builder->createVehicle($category, $vehicle))) {
                                foreach ($this->getBuildResolvers() as $key => $resolver) {
                                    if (in_array($key, array('assets', 'core', 'directory'))) {
                                        if ('directory' == $key) {
                                            foreach ($resolver as $directory) {
                                                $vehicle->resolve('file', $directory);
                                            }
                                        } else {
                                            $vehicle->resolve('file', $resolver);
                                        }
                                    } else {
                                        $vehicle->resolve('php', $resolver);
                                    }

                                    $this->modx->log(xPDO::LOG_LEVEL_INFO, 'Packed "'.$key.'" resolver into category.');
                                }

                                $builder->putVehicle($vehicle);

                                $this->modx->log(modX::LOG_LEVEL_INFO, 'Packed category.');
                            } else {
                                $this->modx->log(modX::LOG_LEVEL_ERROR, 'Could not create the package category vehicle.');
                            }

                            foreach ($this->getBuildAttributes() as $key => $attribute) {
                                $this->modx->log(xPDO::LOG_LEVEL_INFO, 'Attribute "'.$key.'" added to the package.');

                                $builder->setPackageAttributes(array(
                                    $key => $attribute
                                ));
                            }

                            $this->modx->log(xPDO::LOG_LEVEL_INFO, 'Create package transport ZIP.');
                        } else {
                            $this->modx->log(modX::LOG_LEVEL_ERROR, 'Could not create the package category.');
                        }

                        $this->modx->log(modX::LOG_LEVEL_INFO, 'Packaging menu.');

                        if (null !== ($menu = $this->getMenu())) {
                            $vehicle = array(
                                xPDOTransport::PRESERVE_KEYS    => true,
                                xPDOTransport::UPDATE_OBJECT    => true,
                                xPDOTransport::UNIQUE_KEY       => 'text',
                                xPDOTransport::RELATED_OBJECTS  => true
                            );

                            if (null !== ($vehicle = $builder->createVehicle($menu, $vehicle))) {
                                $builder->putVehicle($vehicle);

                                $this->modx->log(modX::LOG_LEVEL_INFO, 'Packed menu.');
                            } else {
                                $this->modx->log(modX::LOG_LEVEL_ERROR, 'Could not create the package menu vehicle.');
                            }
                        }

                        $settings = $this->getSystemSettings();

                        $this->modx->log(modX::LOG_LEVEL_INFO, 'Packaging system settings.');

                        foreach ($settings as $setting) {
                            $vehicle = array(
                                xPDOTransport::PRESERVE_KEYS    => true,
                                xPDOTransport::UPDATE_OBJECT    => false,
                                xPDOTransport::UNIQUE_KEY       => 'key'
                            );

                            if (null !== ($vehicle = $builder->createVehicle($setting, $vehicle))) {
                                $builder->putVehicle($vehicle);
                            }
                        }

                        $this->modx->log(modX::LOG_LEVEL_INFO, 'Packed '.count($settings).' system settings.');

                        if ($this->getPackageEncrypt()) {
                            $builder->putVehicle($builder->createVehicle(array(
                                'source' => __DIR__ . '/resolvers/encryption.resolver.php'
                            ), array(
                                'vehicle_class' => 'xPDOScriptVehicle'
                            )));
                        }

                        if ($builder->pack()) {
                            $this->modx->log(xPDO::LOG_LEVEL_INFO, 'Package transport ZIP created.');
                        } else {
                            $this->modx->log(modX::LOG_LEVEL_ERROR, 'Could not create package transport ZIP.');
                        }
                    } else {
                        $this->modx->log(modX::LOG_LEVEL_ERROR, 'Could not create the package.');
                    }
                } else {
                    $this->modx->log(modX::LOG_LEVEL_ERROR, 'Could not create the package.');
                }
            }

            $time   = explode(' ', microtime());
            $time2  = $time[1] + $time[0];

            $this->modx->log(modX::LOG_LEVEL_INFO, 'Package built execution time '.sprintf('%2.4f s', $time2 - $time1).'.');
        }

        /**
         * @access public.
         * @return String|Boolean.
         */
        public function setPackageEncrypt() {
            if (isset($this->package['encrypt']) && (bool) $this->package['encrypt']) {
                $this->encrypt = true;

                if (defined('PKG_ENCODE_KEY')) {
                    $this->encryptKey = PKG_ENCODE_KEY;
                    return;
                }

                if ($this->encrypt && isset($this->package['encryptProvider'])) {
                    $provider = $this->modx->getObject('transport.modTransportProvider', $this->package['encryptProvider']);

                    if ($provider) {
                        $this->modx->setOption('contentType', 'default');

                        $response = $provider->request('package/encode', 'POST', array(
                            'package'           => $this->package['signature'],
                            'version'           => $this->package['version'],
                            'username'          => $provider->get('username'),
                            'api_key'           => $provider->get('api_key'),
                            'vehicle_version'   => '2.0.0',
                        ));

                        if ($response->isError()) {
                            $this->modx->log(modX::LOG_LEVEL_ERROR, $response->getError());
                        } else {
                            $data = $response->toXml();

                            if (!empty($data->key)) {
                                define('PKG_ENCODE_KEY', (string) $data->key);

                                return $this->encryptKey = (string) $data->key;
                            }

                            $this->modx->log(modX::LOG_LEVEL_ERROR, $data->message);
                        }
                    }
                }
            }

            return false;
        }

        /**
         * @access public.
         * @return Boolean.
         */
        public function getPackageEncrypt() {
            return $this->encrypt && null !== $this->encryptKey;
        }

        /**
         * @access public.
         * @param String $type.
         * @return String.
         */
        public function getPath($type) {
            if (isset($this->path[$type])) {
                if ('root' == $type) {
                    return rtrim($this->path['root'], '/').'/';
                } else {
                    return rtrim($this->path[$type], '/').'/components/'.$this->package['namespace'].'/';
                }
            }

            return '';
        }

        /**
         * @access public.
         * @return Object.
         */
        public function getMenu() {
            if (isset($this->package['menu'])) {
                foreach ($this->package['menu'] as $value) {
                    if (null !== ($menu = $this->modx->newObject('modMenu'))) {
                        $menu->fromArray(array_merge(array(
                            'text'          => $this->package['namespace'],
                            'namespace'     => $this->package['namespace'],
                            'description'   => $this->package['namespace'].'.desc',
                            'parent'        => 'components',
                            'action'        => 'home'
                        ), $value), '', true, true);

                        return $menu;
                    }
                }
            }

            return null;
        }

        /**
         * @access public.
         * @return Array.
         */
        public function getSystemSettings() {
            $output = array();

            if (isset($this->package['settings'])) {
                foreach ($this->package['settings'] as $value) {
                    if (null !== ($object = $this->modx->newObject('modSystemSetting'))) {
                        if (false === strpos($value['key'], '.')) {
                            $value['key'] = $this->package['namespace'].'.'.$value['key'];
                        }

                        $object->fromArray(array_merge(array(
                            'xtype'     => 'textfield',
                            'namespace' => $this->package['namespace'],
                            'area'      => $this->package['namespace']
                        ), $value), '', true, true);

                        $output[] = $object;
                    }
                }
            }

            return $output;
        }

        /**
         * @access public.
         * @return Array.
         */
        public function getElements() {
            $output = array(
                'chunks'    => array(),
                'plugins'   => array(),
                'snippets'  => array(),
                'templates' => array()
            );

            foreach (array_keys($output) as $element) {
                if (isset($this->package['elements'][$element])) {
                    foreach ($this->package['elements'][$element] as $value) {
                        if ('templates' == $element) {
                            if (isset($value['name'])) {
                                $value['templatename'] = $value['name'];
                            }
                        }

                        if (isset($value['resolver'])) {
                            if (file_exists($this->getPath('core').ltrim($value['resolver'], '/'))) {
                                if (false !== ($data = file_get_contents($this->getPath('core').ltrim($value['resolver'], '/')))) {
                                    $value['content'] = trim(str_replace(array('<?php', '?>'), '', $data));
                                }
                            }
                        }

                        if (null !== ($object = $this->getElementsClass($element))) {
                            if (null !== ($elementObject = $this->modx->newObject($object))) {
                                $elementObject->fromArray($value);

                                if (isset($value['properties'])) {
                                    $properties = array();

                                    foreach ($value['properties'] as $property) {
                                        $properties[] = array_merge(array(
                                            'xtype'     => 'textfield',
                                            'lexicon'   => $this->package['namespace'].':default',
                                            'area'      => $this->package['namespace'],
                                            'value'     => ''
                                        ), $property);
                                    }

                                    $elementObject->setProperties($properties);
                                }

                                if (isset($value['events'])) {
                                    foreach ($value['events'] as $event) {
                                        if (null !== ($eventObject = $this->modx->newObject('modPluginEvent'))) {
                                            $eventObject->fromArray(array(
                                                'event'         => $event,
                                                'priority'      => 0,
                                                'propertyset'   => 0
                                            ), '', true, true);

                                            $elementObject->addMany($eventObject);
                                        }
                                    }
                                }

                                $output[$element][] = $elementObject;
                            }
                        }
                    }
                }
            }

            return $output;
        }

        /**
         * @access public.
         * @param String $element.
         * @return Null|String.
         */
        public function getElementsClass($element) {
            if (isset($this->elements[$element])) {
                return $this->elements[$element];
            }

            return null;
        }

        /**
         * @access public.
         * @return Array.
         */
        public function getBuildResolvers() {
            $output = array();

            if (!$this->getPackageEncrypt()) {
                if (is_dir($this->getPath('core'))) {
                    $output['core'] = array(
                        'source'    => $this->getPath('core'),
                        'target'    => 'return MODX_CORE_PATH.\'components/\';'
                    );
                }
            }

            if (is_dir($this->getPath('assets'))) {
                $output['assets'] = array(
                    'source'    => $this->getPath('assets'),
                    'target'    => 'return MODX_ASSETS_PATH.\'components/\';'
                );
            }

            if (isset($this->package['build']['directories'])) {
                foreach ($this->package['build']['directories'] as $directory) {
                    if (is_dir($this->getPath('root').$directory)) {
                        $output['directory'][] = array(
                            'source' => $this->getPath('root').$directory,
                            'target' => "return MODX_BASE_PATH;",
                        );
                    }
                }
            }

            if (isset($this->package['build']['resolvers'])) {
                foreach ($this->package['build']['resolvers'] as $resolver) {
                    $key = substr($resolver, strrpos($resolver, '/') + 1, strlen($resolver));

                    if (file_exists(__DIR__ . '/' . $resolver)) {
                        $output[$key] = array(
                            'source' => __DIR__ . '/' . $resolver
                        );
                    }
                }
            }

            return $output;
        }

        /**
         * @access public.
         * @return Array.
         */
        public function getBuildAttributes() {
            $output = array();

            if (isset($this->package['build']['attributes'])) {
                foreach ($this->attributes as $key) {
                    if (isset($this->package['build']['attributes'][$key])) {
                        $attribute = $this->getPath('core').ltrim($this->package['build']['attributes'][$key], '/');

                        if (file_exists($attribute)) {
                            if (false !== ($data = file_get_contents($attribute))) {
                                $output[$key] = $data;
                            }
                        }
                    }
                }
            }

            if (isset($this->package['build']['setup-options'])) {
                if (file_exists(__DIR__ . '/' . $this->package['build']['setup-options'])) {
                    $output['setup-options'] = array(
                        'source' => __DIR__ . '/' . $this->package['build']['setup-options']
                    );
                }
            }

            return $output;
        }
    }

?>