<?php
/**
 * @package releaseme
 */
$xpdo_meta_map['rmPackage']= array (
  'package' => 'releaseme',
  'table' => 'rm_packages',
  'fields' => 
  array (
    'repository' => '0',
    'parent' => '0',
    'name' => '',
    'description' => NULL,
    'createdon' => NULL,
    'createdby' => '0',
    'use_smf' => '0',
    'use_jira' => '0',
  ),
  'fieldMeta' => 
  array (
    'repository' => 
    array (
      'dbtype' => 'integer',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => 'false',
      'default' => '0',
      'index' => 'index',
    ),
    'parent' => 
    array (
      'dbtype' => 'integer',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => 'false',
      'default' => '0',
      'index' => 'fk',
    ),
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => 'false',
      'default' => '',
      'index' => 'index',
    ),
    'description' => 
    array (
      'dbtype' => 'mediumtext',
      'phptype' => 'string',
      'null' => 'false',
    ),
    'createdon' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => 'false',
    ),
    'createdby' => 
    array (
      'dbtype' => 'integer',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => 'false',
      'default' => '0',
    ),
    'use_smf' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => 'false',
      'default' => '0',
    ),
    'use_jira' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => 'false',
      'default' => '0',
    ),
  ),
  'aggregates' => 
  array (
    'Repository' => 
    array (
      'class' => 'rmRepository',
      'local' => 'repository',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Parent' => 
    array (
      'class' => 'rmPackage',
      'local' => 'parent',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
  'composites' => 
  array (
    'Children' => 
    array (
      'class' => 'rmPackage',
      'local' => 'id',
      'foreign' => 'parent',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'Versions' => 
    array (
      'class' => 'rmPackageVersion',
      'local' => 'id',
      'foreign' => 'package',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
if (XPDO_PHP4_MODE) $xpdo_meta_map['rmPackage']['aggregates']= array_merge($xpdo_meta_map['rmPackage']['aggregates'], array_change_key_case($xpdo_meta_map['rmPackage']['aggregates']));
if (XPDO_PHP4_MODE) $xpdo_meta_map['rmPackage']['composites']= array_merge($xpdo_meta_map['rmPackage']['composites'], array_change_key_case($xpdo_meta_map['rmPackage']['composites']));
$xpdo_meta_map['rmpackage']= & $xpdo_meta_map['rmPackage'];
