<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modNamespace']= array (
  'package' => 'modx',
  'table' => 'namespaces',
  'fields' => 
  array (
    'name' => '',
    'path' => '',
  ),
  'fieldMeta' => 
  array (
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '40',
      'phptype' => 'string',
      'null' => 'false',
      'default' => '',
      'index' => 'pk',
    ),
    'path' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'default' => '',
    ),
  ),
  'aggregates' => 
  array (
    'modLexiconFocus' => 
    array (
      'class' => 'modLexiconFocus',
      'local' => 'name',
      'foreign' => 'namespace',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
if (XPDO_PHP4_MODE) $xpdo_meta_map['modNamespace']['aggregates']= array_merge($xpdo_meta_map['modNamespace']['aggregates'], array_change_key_case($xpdo_meta_map['modNamespace']['aggregates']));
$xpdo_meta_map['modnamespace']= & $xpdo_meta_map['modNamespace'];
