<?php

	$mtime 	= explode(' ', microtime());
	$tstart = $mtime[1] + $mtime[0];

	set_time_limit(0);

	define('PKG_NAME', 			'DigitalSignage');
	define('PKG_NAME_LOWER', 	strtolower(PKG_NAME));
	define('PKG_NAMESPACE', 	strtolower(PKG_NAME));
	define('PKG_VERSION',		'1.1.2');
	define('PKG_RELEASE',		'pl');

	define('PRIVATE_PATH',		dirname(dirname(dirname(__FILE__))).'/');
	define('PUBLIC_PATH',		dirname(dirname(__FILE__)).'/');

	$sources = array(
	    'root' 			=> PRIVATE_PATH,
	    'build' 		=> PUBLIC_PATH.'_build/',
	    'data' 			=> PUBLIC_PATH.'_build/data/',
	    'resolvers' 	=> PUBLIC_PATH.'_build/resolvers/',
	    'core' 			=> PRIVATE_PATH.'core/components/'.PKG_NAME_LOWER,
	    'assets' 		=> PUBLIC_PATH.'assets/components/'.PKG_NAME_LOWER,
	    'chunks' 		=> PRIVATE_PATH.'core/components/'.PKG_NAME_LOWER.'/elements/chunks/',
	    'cronjobs' 		=> PRIVATE_PATH.'core/components/'.PKG_NAME_LOWER.'/elements/cronjobs/',
	    'plugins' 		=> PRIVATE_PATH.'core/components/'.PKG_NAME_LOWER.'/elements/plugins/',
	    'snippets' 		=> PRIVATE_PATH.'core/components/'.PKG_NAME_LOWER.'/elements/snippets/',
	    'widgets' 		=> PRIVATE_PATH.'core/components/'.PKG_NAME_LOWER.'/elements/widgets/',
        'templates' 	=> PRIVATE_PATH.'core/components/'.PKG_NAME_LOWER.'/elements/templates/',
	    'lexicon' 		=> PRIVATE_PATH.'core/components/'.PKG_NAME_LOWER.'/lexicon/',
	    'docs' 			=> PRIVATE_PATH.'core/components/'.PKG_NAME_LOWER.'/docs/',
        'digitalsignage' => PUBLIC_PATH.'digitalsignage/'
	);

	require_once $sources['build'].'/includes/functions.php';
	require_once PRIVATE_PATH.'core/config/config.inc.php';
	require_once PRIVATE_PATH.'core/model/modx/modx.class.php';

	$modx = new modX();
	$modx->initialize('mgr');
	$modx->setLogLevel(modX::LOG_LEVEL_INFO);
	$modx->setLogTarget('ECHO');

	echo XPDO_CLI_MODE ? '' : '<pre>';

	$modx->loadClass('transport.modPackageBuilder', '', false, true);

	$builder = new modPackageBuilder($modx);
	$builder->createPackage(PKG_NAMESPACE, PKG_VERSION, PKG_RELEASE);
	$builder->registerNamespace(PKG_NAMESPACE, false, true, '{core_path}components/'.PKG_NAMESPACE.'/');

	$modx->log(modX::LOG_LEVEL_INFO, 'Packaging category...');

	$category = $modx->newObject('modCategory');
	$category->fromArray(array('id' => 1, 'category' => PKG_NAME), '', true, true);

	if (file_exists($sources['data'].'transport.chunks.php')) {
        $chunks = include $sources['data'].'transport.chunks.php';

        if (count($chunks) > 0) {
            $modx->log(modX::LOG_LEVEL_INFO, 'Packaging chunk(s) into category...');

            foreach ($chunks as $chunk) {
                $category->addMany($chunk);
            }

            $modx->log(modX::LOG_LEVEL_INFO, 'Packed ' . count($chunks) . ' chunk(s) into category.');
        } else {
            $modx->log(modX::LOG_LEVEL_INFO, 'No chunk(s) to pack into category...');
        }
	} else {
		$modx->log(modX::LOG_LEVEL_INFO, 'No chunk(s) to pack into category...');
	}

	if (file_exists($sources['data'].'transport.cronjobs.php')) {
        $cronjobs = include $sources['data'].'transport.cronjobs.php';

        if (count($cronjobs) > 0) {
            $modx->log(modX::LOG_LEVEL_INFO, 'Packaging cronjobs(s) into category...');

            foreach ($cronjobs as $cronjob) {
                $category->addMany($cronjob);
            }

            $modx->log(modX::LOG_LEVEL_INFO, 'Packed '.count($cronjobs).' cronjobs(s) into category.');
        } else {
            $modx->log(modX::LOG_LEVEL_INFO, 'No cronjobs(s) to pack into category...');
        }
	} else {
		$modx->log(modX::LOG_LEVEL_INFO, 'No cronjobs(s) to pack into category...');
	}

	if (file_exists($sources['data'].'transport.plugins.php')) {
        $plugins = include $sources['data'].'transport.plugins.php';

        if (count($plugins) > 0) {
            $modx->log(modX::LOG_LEVEL_INFO, 'Packaging plugins(s) into category...');

            foreach ($plugins as $plugin) {
                $category->addMany($plugin);
            }

            $modx->log(modX::LOG_LEVEL_INFO, 'Packed '.count($plugins).' plugins(s) into category.');
        } else {
            $modx->log(modX::LOG_LEVEL_INFO, 'No plugins(s) to pack into category...');
        }
	} else {
		$modx->log(modX::LOG_LEVEL_INFO, 'No plugins(s) to pack into category...');
	}

	if (file_exists($sources['data'].'transport.snippets.php')) {
        $snippets = include $sources['data'].'transport.snippets.php';

        if (count($snippets) > 0) {
            $modx->log(modX::LOG_LEVEL_INFO, 'Packaging snippet(s) into category...');

            foreach ($snippets as $snippet) {
                $category->addMany($snippet);
            }

            $modx->log(modX::LOG_LEVEL_INFO, 'Packed '.count($snippets).' snippet(s) into category.');
        } else {
            $modx->log(modX::LOG_LEVEL_INFO, 'No snippet(s) to pack into category...');
        }
	} else {
		$modx->log(modX::LOG_LEVEL_INFO, 'No snippet(s) to pack into category...');
	}

    if (file_exists($sources['data'].'transport.templates.php')) {
        $templates = include $sources['data'].'transport.templates.php';

        if (count($templates) > 0) {
            $modx->log(modX::LOG_LEVEL_INFO, 'Packaging template(s) into category...');

            foreach ($templates as $template) {
                $category->addMany($template);
            }

            $modx->log(modX::LOG_LEVEL_INFO, 'Packed '.count($templates).' template(s) into category.');
        } else {
            $modx->log(modX::LOG_LEVEL_INFO, 'No template(s) to pack into category...');
        }
    } else {
        $modx->log(modX::LOG_LEVEL_INFO, 'No template(s) to pack into category...');
    }

	$vehicle = $builder->createVehicle($category, array(
	    xPDOTransport::UNIQUE_KEY 		=> 'category',
	    xPDOTransport::PRESERVE_KEYS 	=> false,
	    xPDOTransport::UPDATE_OBJECT 	=> true,
	    xPDOTransport::RELATED_OBJECTS 	=> true,
	    xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array(
		    'Chunks' => array(
	            xPDOTransport::PRESERVE_KEYS 	=> false,
	            xPDOTransport::UPDATE_OBJECT 	=> true,
	            xPDOTransport::UNIQUE_KEY 		=> 'name'
	        ),
	        'Plugins' => array(
	            xPDOTransport::PRESERVE_KEYS 	=> false,
	            xPDOTransport::UPDATE_OBJECT 	=> true,
	            xPDOTransport::UNIQUE_KEY 		=> 'name'
	        ),
	        'PluginEvents' => array(
	            xPDOTransport::PRESERVE_KEYS 	=> true,
	            xPDOTransport::UPDATE_OBJECT 	=> false,
	            xPDOTransport::UNIQUE_KEY 		=> array('pluginid', 'event'),
	        ),
	        'Snippets' => array(
	            xPDOTransport::PRESERVE_KEYS 	=> false,
	            xPDOTransport::UPDATE_OBJECT 	=> true,
	            xPDOTransport::UNIQUE_KEY 		=> 'name'
	        ),
            'Templates' => array(
                xPDOTransport::PRESERVE_KEYS 	=> false,
                xPDOTransport::UPDATE_OBJECT 	=> true,
                xPDOTransport::UNIQUE_KEY 		=> 'templatename'
            )
	    )
	));

    $modx->log(modX::LOG_LEVEL_INFO, 'Packaging PHP resolvers into category...');

    if (is_dir($sources['assets'])) {
        $vehicle->resolve('file', array(
            'source' => $sources['assets'],
            'target' => "return MODX_ASSETS_PATH.'components/';",
        ));

        $modx->log(modX::LOG_LEVEL_INFO, 'Packed '.$sources['assets'].' resolver into category.');
    }

    if (is_dir($sources['core'])) {
        $vehicle->resolve('file', array(
            'source' => $sources['core'],
            'target' => "return MODX_CORE_PATH.'components/';",
        ));

        $modx->log(modX::LOG_LEVEL_INFO, 'Packed '.$sources['core'].' resolver into category.');
    }

    if (is_dir($sources['digitalsignage'])) {
        $vehicle->resolve('file', array(
            'source' => $sources['digitalsignage'],
            'target' => "return MODX_BASE_PATH;",
        ));

        $modx->log(modX::LOG_LEVEL_INFO, 'Packed '.$sources['digitalsignage'].' resolver into category.');
    }

    if (is_dir($sources['resolvers'])) {
        foreach (glob($sources['resolvers'] . '*.php') as $file) {
            $vehicle->resolve('php', array(
                'source' => $file
            ));
        }
    }

    $builder->putVehicle($vehicle);

    $modx->log(modX::LOG_LEVEL_INFO, 'Packed category.');

	if (file_exists($sources['data'].'transport.widgets.php')) {
        $widgets = include $sources['data'].'transport.widgets.php';

        if (count($widgets) > 0) {
            $modx->log(modX::LOG_LEVEL_INFO, 'Packaging widgets(s)...');

            foreach ($widgets as $key => $value) {
                $builder->putVehicle($builder->createVehicle($value, array(
                    xPDOTransport::UNIQUE_KEY 		=> 'name',
                    xPDOTransport::PRESERVE_KEYS 	=> false,
                    xPDOTransport::UPDATE_OBJECT 	=> true
                )));
            }

            $modx->log(modX::LOG_LEVEL_INFO, 'Packed '.count($widgets).' widgets(s).');
        } else {
            $modx->log(modX::LOG_LEVEL_INFO, 'No widgets(s) to pack...');
        }
	} else {
		$modx->log(modX::LOG_LEVEL_INFO, 'No widgets(s) to pack...');
	}

    if (file_exists($sources['data'].'transport.contexts.php')) {
        $contexts = include $sources['data'].'transport.contexts.php';

        if (count($contexts) > 0) {
            $modx->log(modX::LOG_LEVEL_INFO, 'Packaging context(s)...');

            foreach ($contexts as $key => $value) {
                $builder->putVehicle($builder->createVehicle($value, array(
                    xPDOTransport::UNIQUE_KEY => 'key',
                    xPDOTransport::PRESERVE_KEYS => true,
                    xPDOTransport::UPDATE_OBJECT => false,
                    xPDOTransport::RELATED_OBJECTS 	=> true,
                    xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array(
                        'ContextSettings' => array(
                            xPDOTransport::PRESERVE_KEYS 	=> true,
                            xPDOTransport::UPDATE_OBJECT 	=> false,
                            xPDOTransport::UNIQUE_KEY 		=> array('context_key', 'key')
                        )
                    )
                )));
            }

            $modx->log(modX::LOG_LEVEL_INFO, 'Packed '.count($contexts).' context(s).');
        } else {
            $modx->log(modX::LOG_LEVEL_INFO, 'No context(s) to pack...');
        }
    } else {
        $modx->log(modX::LOG_LEVEL_INFO, 'No context(s) to pack...');
    }

    if (file_exists($sources['data'].'transport.resources.php')) {
        $resources = include $sources['data'].'transport.resources.php';

        if (count($resources) > 0) {
            $modx->log(modX::LOG_LEVEL_INFO, 'Packaging resource(s)...');

            foreach ($resources as $key => $value) {
                $builder->putVehicle($builder->createVehicle($value, array(
                    xPDOTransport::UNIQUE_KEY => 'id',
                    xPDOTransport::PRESERVE_KEYS => true,
                    xPDOTransport::UPDATE_OBJECT => false
                )));
            }

            $modx->log(modX::LOG_LEVEL_INFO, 'Packed '.count($resources).' resource(s).');
        } else {
            $modx->log(modX::LOG_LEVEL_INFO, 'No resource(s) to pack...');
        }
    } else {
        $modx->log(modX::LOG_LEVEL_INFO, 'No resource(s) to pack...');
    }

	if (file_exists($sources['data'].'transport.settings.php')) {
        $settings = include $sources['data'].'transport.settings.php';

        if (count($settings) > 0) {
            $modx->log(modX::LOG_LEVEL_INFO, 'Packaging systemsetting(s)...');

            foreach ($settings as $key => $value) {
                $builder->putVehicle($builder->createVehicle($value, array(
                    xPDOTransport::UNIQUE_KEY => 'key',
                    xPDOTransport::PRESERVE_KEYS => true,
                    xPDOTransport::UPDATE_OBJECT => false
                )));
            }

            $modx->log(modX::LOG_LEVEL_INFO, 'Packed '.count($settings).' systemsetting(s).');
        } else {
            $modx->log(modX::LOG_LEVEL_INFO, 'No systemsetting(s) to pack...');
        }
	} else {
		$modx->log(modX::LOG_LEVEL_INFO, 'No systemsetting(s) to pack...');
	}

	if (file_exists($sources['data'].'transport.menu.php')) {
		$menu = include $sources['data'].'transport.menu.php';

		if (null === $menu) {
			$modx->log(modX::LOG_LEVEL_ERROR, 'No menu to pack.');
		} else {
            $modx->log(modX::LOG_LEVEL_INFO, 'Packaging menu...');

            $builder->putVehicle($builder->createVehicle($menu, array(
			    xPDOTransport::PRESERVE_KEYS 	=> true,
			    xPDOTransport::UPDATE_OBJECT 	=> true,
			    xPDOTransport::UNIQUE_KEY 		=> 'text',
			    xPDOTransport::RELATED_OBJECTS 	=> true,
			    xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array(
			        'Action' => array(
			            xPDOTransport::PRESERVE_KEYS 	=> false,
			            xPDOTransport::UPDATE_OBJECT 	=> true,
			            xPDOTransport::UNIQUE_KEY 		=> array('namespace','controller')
			        ),
			    ),
			)));

			$modx->log(modX::LOG_LEVEL_INFO, 'Packed menu.');
		}
	}

	$modx->log(xPDO::LOG_LEVEL_INFO, 'Setting Package Attributes...');

	if (file_exists($sources['build'].'/setup.options.php')) {
		$builder->setPackageAttributes(array(
		    'license' 		=> file_get_contents($sources['docs'].'license.txt'),
		    'readme' 		=> file_get_contents($sources['docs'].'readme.txt'),
		    'changelog' 	=> file_get_contents($sources['docs'].'changelog.txt'),
		    'setup-options' => array(
	        	'source' 		=> $sources['build'].'/setup.options.php'
			)
		));
	} else {
		$builder->setPackageAttributes(array(
		    'license' 		=> file_get_contents($sources['docs'].'license.txt'),
		    'readme' 		=> file_get_contents($sources['docs'].'readme.txt'),
		    'changelog' 	=> file_get_contents($sources['docs'].'changelog.txt')
		));
	}

	$modx->log(xPDO::LOG_LEVEL_INFO, 'Zipping up package...');

	$builder->pack();

	$mtime		= explode(' ', microtime());
	$tend		= $mtime[1] + $mtime[0];
	$totalTime	= ($tend - $tstart);
	$totalTime	= sprintf("%2.4f s", $totalTime);

	$modx->log(modX::LOG_LEVEL_INFO, 'Package Built: Execution time: {'.$totalTime.'}');

	echo XPDO_CLI_MODE ? '' : '</pre>';

	exit();

?>
