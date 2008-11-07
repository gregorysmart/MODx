<?php
/**
 * @package releaseme
 */
$xpdo_meta_map['rmRepository']= array (
  'package' => 'releaseme',
  'table' => 'rm_repositories',
  'fields' => 
  array (
    'name' => '',
    'createdon' => NULL,
  ),
  'fieldMeta' => 
  array (
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => 'false',
      'default' => '',
      'index' => 'index',
    ),
    'createdon' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => 'false',
    ),
  ),
  'composites' => 
  array (
    'Packages' => 
    array (
      'class' => 'rmPackage',
      'local' => 'id',
      'foreign' => 'repository',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
if (XPDO_PHP4_MODE) $xpdo_meta_map['rmRepository']['composites']= array_merge($xpdo_meta_map['rmRepository']['composites'], array_change_key_case($xpdo_meta_map['rmRepository']['composites']));
$xpdo_meta_map['rmrepository']= & $xpdo_meta_map['rmRepository'];
