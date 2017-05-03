<?php
/**
 * @package usermanagement
 */
$xpdo_meta_map['userManagementItem']= array (
  'package' => 'usermanagement',
  'version' => NULL,
  'table' => 'usermanagement_items',
  'extends' => 'xPDOSimpleObject',
  'fields' =>
  array (
    'name' => '',
    'description' => '',
    'position' => NULL,
  ),
  'fieldMeta' =>
  array (
    'name' =>
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'description' =>
    array (
      'dbtype' => 'text',
      'phptype' => 'text',
      'null' => false,
      'default' => '',
    ),
    'position' =>
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
    ),
  ),
);
