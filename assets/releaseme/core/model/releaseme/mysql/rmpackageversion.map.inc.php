<?php
/**
 * @package releaseme
 */
$xpdo_meta_map['rmPackageVersion']= array (
  'package' => 'releaseme',
  'table' => 'rm_package_versions',
  'fields' => 
  array (
    'package' => NULL,
    'display_name' => NULL,
    'version' => NULL,
    'release' => NULL,
    'author' => '0',
    'description' => NULL,
    'createdon' => NULL,
    'createdby' => '0',
    'editedon' => NULL,
    'editedby' => '0',
    'releasedon' => NULL,
    'website_url' => NULL,
    'downloads' => '0',
    'audited' => '0',
    'featured' => '0',
    'deprecated' => '0',
    'license' => 'GPLv3',
    'smf_url' => NULL,
  ),
  'fieldMeta' => 
  array (
    'package' => 
    array (
      'dbtype' => 'integer',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => 'false',
      'index' => 'fk',
    ),
    'display_name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => 'false',
    ),
    'version' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '40',
      'phptype' => 'string',
      'null' => 'false',
    ),
    'release' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '40',
      'phptype' => 'string',
      'null' => 'false',
    ),
    'author' => 
    array (
      'dbtype' => 'integer',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => 'false',
      'default' => '0',
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
    'editedon' => 
    array (
      'dbtype' => 'timestamp',
      'phptype' => 'timestamp',
      'attributes' => 'ON UPDATE CURRENT_TIMESTAMP',
    ),
    'editedby' => 
    array (
      'dbtype' => 'integer',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => 'false',
      'default' => '0',
    ),
    'releasedon' => 
    array (
      'dbtype' => 'timestamp',
      'phptype' => 'timestamp',
      'null' => 'true',
    ),
    'website_url' => 
    array (
      'dbtype' => 'tinytext',
      'phptype' => 'string',
      'null' => 'false',
    ),
    'downloads' => 
    array (
      'dbtype' => 'integer',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => 'false',
      'default' => '0',
    ),
    'audited' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => 'false',
      'default' => '0',
    ),
    'featured' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => 'false',
      'default' => '0',
    ),
    'deprecated' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => 'false',
      'default' => '0',
    ),
    'license' => 
    array (
      'dbtype' => 'varchar',
      'phptype' => 'string',
      'null' => 'false',
      'default' => 'GPLv3',
    ),
    'smf_url' => 
    array (
      'dbtype' => 'mediumtext',
      'phptype' => 'string',
      'nnull' => 'false',
    ),
  ),
  'aggregates' => 
  array (
    'Package' => 
    array (
      'class' => 'rmPackage',
      'local' => 'package',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Author' => 
    array (
      'class' => 'modUser',
      'local' => 'author',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Creator' => 
    array (
      'class' => 'modUser',
      'local' => 'createdby',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Editor' => 
    array (
      'class' => 'modUser',
      'local' => 'editedby',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
  'composites' => 
  array (
    'Supports' => 
    array (
      'class' => 'rmPackageSupports',
      'local' => 'id',
      'foreign' => 'package_version',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
if (XPDO_PHP4_MODE) $xpdo_meta_map['rmPackageVersion']['aggregates']= array_merge($xpdo_meta_map['rmPackageVersion']['aggregates'], array_change_key_case($xpdo_meta_map['rmPackageVersion']['aggregates']));
if (XPDO_PHP4_MODE) $xpdo_meta_map['rmPackageVersion']['composites']= array_merge($xpdo_meta_map['rmPackageVersion']['composites'], array_change_key_case($xpdo_meta_map['rmPackageVersion']['composites']));
$xpdo_meta_map['rmpackageversion']= & $xpdo_meta_map['rmPackageVersion'];
