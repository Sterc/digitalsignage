<?php

	return array(
		array(
            'key' 		=> 'base_url',
            'value' 	=> '/nc/',
            'xtype' 	=> 'textfield',
            'namespace' => 'core'
		),
        array(
            'key' 		=> 'site_status',
            'value' 	=> 'true',
            'xtype' 	=> 'combo-boolean',
            'namespace' => 'core'
        ),
        array(
            'key' 		=> 'site_url',
            'value' 	=> 'http://{http_host}/nc/',
            'xtype' 	=> 'textfield',
            'namespace' => 'core'
        )
	);

?>