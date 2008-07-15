<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modResourceKeyword']= array (
  'package' => 'modx',
  'table' => 'keyword_xref',
  'fields' => 
  array (
    'content_id' => '0',
    'keyword_id' => '0',
  ),
  'fieldMeta' => 
  array (
    'content_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => 'false',
      'default' => '0',
      'index' => 'pk',
    ),
    'keyword_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => 'false',
      'default' => '0',
      'index' => 'pk',
    ),
  ),
  'aggregates' => 
  array (
    'modResource' => 
    array (
      'class' => 'modResource',
      'key' => 'id',
      'local' => 'content_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'modKeyword' => 
    array (
      'class' => 'modKeyword',
      'key' => 'id',
      'local' => 'keyword_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
if (XPDO_PHP4_MODE) $xpdo_meta_map['modResourceKeyword']['aggregates']= array_merge($xpdo_meta_map['modResourceKeyword']['aggregates'], array_change_key_case($xpdo_meta_map['modResourceKeyword']['aggregates']));
$xpdo_meta_map['modresourcekeyword']= & $xpdo_meta_map['modResourceKeyword'];
