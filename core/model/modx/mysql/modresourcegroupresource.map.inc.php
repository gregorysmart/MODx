<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modResourceGroupResource']= array (
  'package' => 'modx',
  'table' => 'document_groups',
  'fields' => 
  array (
    'document_group' => 0,
    'document' => 0,
  ),
  'fieldMeta' => 
  array (
    'document_group' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'document' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
  ),
  'aggregates' => 
  array (
    'modResourceGroup' => 
    array (
      'class' => 'modResourceGroup',
      'key' => 'id',
      'local' => 'document_group',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'modResource' => 
    array (
      'class' => 'modResource',
      'key' => 'id',
      'local' => 'document',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
if (XPDO_PHP4_MODE) $xpdo_meta_map['modResourceGroupResource']['aggregates']= array_merge($xpdo_meta_map['modResourceGroupResource']['aggregates'], array_change_key_case($xpdo_meta_map['modResourceGroupResource']['aggregates']));
$xpdo_meta_map['modresourcegroupresource']= & $xpdo_meta_map['modResourceGroupResource'];
