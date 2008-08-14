<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modSystemSetting']= array (
  'package' => 'modx',
  'table' => 'system_settings',
  'fields' => 
  array (
    'key' => '',
    'value' => '',
    'xtype' => 'textfield',
    'namespace' => 'core',
    'editedon' => NULL,
  ),
  'fieldMeta' => 
  array (
    'key' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '50',
      'phptype' => 'string',
      'null' => 'false',
      'default' => '',
      'index' => 'pk',
    ),
    'value' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => 'false',
      'default' => '',
    ),
    'xtype' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '75',
      'phptype' => 'string',
      'null' => 'false',
      'default' => 'textfield',
    ),
    'namespace' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '40',
      'phptype' => 'string',
      'null' => 'false',
      'default' => 'core',
    ),
    'editedon' => 
    array (
      'dbtype' => 'timestamp',
      'phptype' => 'timestamp',
      'null' => 'false',
      'attributes' => 'ON UPDATE CURRENT_TIMESTAMP',
    ),
  ),
);
$xpdo_meta_map['modsystemsetting']= & $xpdo_meta_map['modSystemSetting'];
