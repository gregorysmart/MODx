<?php
/**
 * @package releaseme
 */
$xpdo_meta_map['rmPackageSupports']= array (
  'package' => 'releaseme',
  'table' => 'rm_package_supports',
  'fields' => 
  array (
    'package_version' => NULL,
    'version' => NULL,
  ),
  'fieldMeta' => 
  array (
    'package_version' => 
    array (
      'dbtype' => 'integer',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => 'false',
      'index' => 'pk',
    ),
    'version' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => 'false',
    ),
  ),
  'aggregates' => 
  array (
    'PackageVersion' => 
    array (
      'class' => 'rmPackageVersion',
      'local' => 'package_version',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
if (XPDO_PHP4_MODE) $xpdo_meta_map['rmPackageSupports']['aggregates']= array_merge($xpdo_meta_map['rmPackageSupports']['aggregates'], array_change_key_case($xpdo_meta_map['rmPackageSupports']['aggregates']));
$xpdo_meta_map['rmpackagesupports']= & $xpdo_meta_map['rmPackageSupports'];
