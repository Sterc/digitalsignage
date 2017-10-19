<?php

	$templates = array();
	
	foreach (glob($sources['templates'].'/*.tpl') as $key => $value) {
		$name = str_replace('.template.tpl', '', substr($value, strrpos($value, '/') + 1, strlen($value)));

        $templates[$name] = $modx->newObject('modTemplate');
        $templates[$name]->fromArray(array(
			'id' 			=> 1,
			'templatename'	=> ucfirst($name),
			'description'	=> PKG_NAME.' '.PKG_VERSION.'-'.PKG_RELEASE.' template for MODx Revolution',
			'icon'          => 'icon-play-circle',
			'content'		=> getSnippetContent($value)
		));
	}
	
	return $templates;

?>