<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modPropertySet']= array (
  'package' => 'modx',
  'table' => 'property_set',
  'fields' => 
  array (
    'name' => '',
    'description' => '',
    'properties' => NULL,
  ),
  'fieldMeta' => 
  array (
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '50',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'unique',
    ),
    'description' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'properties' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'array',
      'null' => true,
    ),
  ),
  'composites' => 
  array (
    'Elements' => 
    array (
      'class' => 'modElementPropertySet',
      'local' => 'id',
      'foreign' => 'property_set',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
if (XPDO_PHP4_MODE) $xpdo_meta_map['modPropertySet']['composites']= array_merge($xpdo_meta_map['modPropertySet']['composites'], array_change_key_case($xpdo_meta_map['modPropertySet']['composites']));
$xpdo_meta_map['modpropertyset']= & $xpdo_meta_map['modPropertySet'];
