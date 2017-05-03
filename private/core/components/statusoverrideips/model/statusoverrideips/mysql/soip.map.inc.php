<?php
$xpdo_meta_map['soIP']= array (
  'package' => 'statusoverrideips',
  'version' => NULL,
  'table' => 'statusoverrideips_ips',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'name' => '',
    'ip' => '',
  ),
  'fieldMeta' => 
  array (
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'ip' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '46',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
  ),
);
