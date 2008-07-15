<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modModuleDepobj']= array (
  'package' => 'modx',
  'table' => 'site_module_depobj',
  'fields' => 
  array (
    'module' => '0',
    'resource' => '0',
    'type' => '0',
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
    'resource' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => 'false',
      'default' => '0',
      'index' => 'fk',
    ),
    'type' => 
    array (
      'dbtype' => 'int',
      'precision' => '2',
      'phptype' => 'integer',
      'null' => 'false',
      'default' => '0',
      'index' => 'index',
    ),
  ),
  'aggregates' => 
  array (
    'Module' => 
    array (
      'class' => 'modModule',
      'local' => 'module',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
if (XPDO_PHP4_MODE) $xpdo_meta_map['modModuleDepobj']['aggregates']= array_merge($xpdo_meta_map['modModuleDepobj']['aggregates'], array_change_key_case($xpdo_meta_map['modModuleDepobj']['aggregates']));
$xpdo_meta_map['modmoduledepobj']= & $xpdo_meta_map['modModuleDepobj'];
