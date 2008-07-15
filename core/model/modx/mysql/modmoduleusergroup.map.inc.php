<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modModuleUserGroup']= array (
  'package' => 'modx',
  'table' => 'site_module_access',
  'fields' => 
  array (
    'module' => '0',
    'usergroup' => '0',
  ),
  'fieldMeta' => 
  array (
    'module' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => 'false',
      'default' => '0',
      'index' => 'fk',
    ),
    'usergroup' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => 'false',
      'default' => '0',
      'index' => 'fk',
    ),
  ),
  'aggregates' => 
  array (
    'modModule' => 
    array (
      'class' => 'modModule',
      'key' => 'id',
      'local' => 'module',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'modUserGroup' => 
    array (
      'class' => 'modUserGroup',
      'key' => 'id',
      'local' => 'usergroup',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
if (XPDO_PHP4_MODE) $xpdo_meta_map['modModuleUserGroup']['aggregates']= array_merge($xpdo_meta_map['modModuleUserGroup']['aggregates'], array_change_key_case($xpdo_meta_map['modModuleUserGroup']['aggregates']));
$xpdo_meta_map['modmoduleusergroup']= & $xpdo_meta_map['modModuleUserGroup'];
