<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modUserGroupDocumentGroup']= array (
  'package' => 'modx',
  'table' => 'membergroup_access',
  'fields' => 
  array (
    'membergroup' => 0,
    'documentgroup' => 0,
  ),
  'fieldMeta' => 
  array (
    'membergroup' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'documentgroup' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
  ),
  'aggregates' => 
  array (
    'UserGroup' => 
    array (
      'class' => 'modUserGroup',
      'key' => 'id',
      'local' => 'membergroup',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'DocumentGroup' => 
    array (
      'class' => 'modDocumentGroup',
      'key' => 'id',
      'local' => 'documentgroup',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
if (XPDO_PHP4_MODE) $xpdo_meta_map['modUserGroupDocumentGroup']['aggregates']= array_merge($xpdo_meta_map['modUserGroupDocumentGroup']['aggregates'], array_change_key_case($xpdo_meta_map['modUserGroupDocumentGroup']['aggregates']));
$xpdo_meta_map['modusergroupdocumentgroup']= & $xpdo_meta_map['modUserGroupDocumentGroup'];
