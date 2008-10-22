<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modModule']= array (
  'package' => 'modx',
  'table' => 'site_modules',
  'fields' => 
  array (
    'disabled' => '0',
    'wrap' => '0',
    'locked' => '0',
    'icon' => '',
    'enable_resource' => '0',
    'resourcefile' => '',
    'createdon' => '0',
    'editedon' => '0',
    'guid' => '',
    'enable_sharedparams' => '0',
    'properties' => NULL,
    'modulecode' => NULL,
  ),
  'fieldMeta' => 
  array (
    'disabled' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'boolean',
      'null' => 'false',
      'default' => '0',
    ),
    'wrap' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'boolean',
      'null' => 'false',
      'default' => '0',
    ),
    'locked' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'boolean',
      'null' => 'false',
      'default' => '0',
    ),
    'icon' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => 'false',
      'default' => '',
    ),
    'enable_resource' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'boolean',
      'null' => 'false',
      'default' => '0',
    ),
    'resourcefile' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => 'false',
      'default' => '',
    ),
    'createdon' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'timestamp',
      'null' => 'false',
      'default' => '0',
    ),
    'editedon' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'timestamp',
      'null' => 'false',
      'default' => '0',
    ),
    'guid' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '32',
      'phptype' => 'string',
      'null' => 'false',
      'default' => '',
    ),
    'enable_sharedparams' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'boolean',
      'null' => 'false',
      'default' => '0',
    ),
    'properties' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'array',
      'null' => 'true',
    ),
    'modulecode' => 
    array (
      'dbtype' => 'mediumtext',
      'phptype' => 'string',
    ),
  ),
  'composites' => 
  array (
    'modModuleDepobj' => 
    array (
      'class' => 'modModuleDepobj',
      'key' => 'id',
      'local' => 'id',
      'foreign' => 'module',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'modModuleUserGroup' => 
    array (
      'class' => 'modModuleUserGroup',
      'key' => 'id',
      'local' => 'id',
      'foreign' => 'module',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
  'aggregates' => 
  array (
    'modPlugin' => 
    array (
      'class' => 'modPlugin',
      'key' => 'guid',
      'local' => 'guid',
      'foreign' => 'moduleguid',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
if (XPDO_PHP4_MODE) $xpdo_meta_map['modModule']['aggregates']= array_merge($xpdo_meta_map['modModule']['aggregates'], array_change_key_case($xpdo_meta_map['modModule']['aggregates']));
if (XPDO_PHP4_MODE) $xpdo_meta_map['modModule']['composites']= array_merge($xpdo_meta_map['modModule']['composites'], array_change_key_case($xpdo_meta_map['modModule']['composites']));
$xpdo_meta_map['modmodule']= & $xpdo_meta_map['modModule'];
