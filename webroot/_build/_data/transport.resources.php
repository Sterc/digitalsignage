<?php

    $resources = array();

    $resources[0] = $modx->newObject('modResource');
    $resources[0]->fromArray(array(
        'context_key'   => 'nc',
        'pagetitle'     => 'Home',
        'published'     => 1,
        'deleted'       => 0,
        'hidemenu'      => 0,
        'richtext'      => 1,
        'template'      => '',
        'content'       => '',
        'menuindex'     => 0
    ));

    $resources[1] = $modx->newObject('modResource');
    $resources[1]->fromArray(array(
         'context_key'  => 'nc',
         'pagetitle'    => 'Export',
         'published'    => 1,
         'deleted'      => 0,
         'hidemenu'     => 1,
         'richtext'     => 0,
         'template'     => '',
         'content'      => '[[!Narrowcasting]]',
         'menuindex'    => 1,
         'content_type' => 7
     ));

    return $resources;

?>