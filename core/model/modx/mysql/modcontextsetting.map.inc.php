<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modContextSetting']= array (
  'package' => 'modx',
  'table' => 'context_setting',
  'fields' => 
  array (
    'context_key' => NULL,
    'key' => NULL,
    'value' => NULL,
    'xtype' => 'textfield',
    'namespace' => 'core',
    'editedon' => NULL,
  ),
  'fieldMeta' => 
  array (
    'context_key' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => 'false',
      'index' => 'pk',
    ),
    'key' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '50',
      'phptype' => 'string',
      'null' => 'false',
      'index' => 'pk',
    ),
    'value' => 
    array (
      'dbtype' => 'mediumtext',
      'phptype' => 'string',
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
  'aggregates' => 
  array (
    'modContext' => 
    array (
      'class' => 'modContext',
      'key' => 'context_key',
      'local' => 'context_key',
      'foreign' => 'key',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'modSystemSetting' => 
    array (
      'class' => 'modSystemSetting',
      'key' => 'key',
      'local' => 'key',
      'foreign' => 'key',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
if (XPDO_PHP4_MODE) $xpdo_meta_map['modContextSetting']['aggregates']= array_merge($xpdo_meta_map['modContextSetting']['aggregates'], array_change_key_case($xpdo_meta_map['modContextSetting']['aggregates']));
$xpdo_meta_map['modcontextsetting']= & $xpdo_meta_map['modContextSetting'];
