<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modCategory']= array (
  'package' => 'modx',
  'table' => 'categories',
  'fields' => 
  array (
    'category' => '',
  ),
  'fieldMeta' => 
  array (
    'category' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '45',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'unique',
    ),
  ),
  'aggregates' => 
  array (
    'modChunk' => 
    array (
      'class' => 'modChunk',
      'key' => 'id',
      'local' => 'id',
      'foreign' => 'category',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'modSnippet' => 
    array (
      'class' => 'modSnippet',
      'key' => 'id',
      'local' => 'id',
      'foreign' => 'category',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'modPlugin' => 
    array (
      'class' => 'modPlugin',
      'key' => 'id',
      'local' => 'id',
      'foreign' => 'category',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'modTemplate' => 
    array (
      'class' => 'modTemplate',
      'key' => 'id',
      'local' => 'id',
      'foreign' => 'category',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'modTemplateVar' => 
    array (
      'class' => 'modTemplateVar',
      'key' => 'id',
      'local' => 'id',
      'foreign' => 'category',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'modPropertySet' => 
    array (
      'class' => 'modPropertySet',
      'local' => 'id',
      'foreign' => 'category',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
  'validation' => 
  array (
    'rules' => 
    array (
      'category' => 
      array (
        'preventBlank' => 
        array (
          'type' => 'xPDOValidationRule',
          'rule' => 'xPDOMinLengthValidationRule',
          'value' => '1',
          'message' => 'category_err_ns_name',
        ),
      ),
    ),
  ),
);
if (XPDO_PHP4_MODE) $xpdo_meta_map['modCategory']['aggregates']= array_merge($xpdo_meta_map['modCategory']['aggregates'], array_change_key_case($xpdo_meta_map['modCategory']['aggregates']));
$xpdo_meta_map['modcategory']= & $xpdo_meta_map['modCategory'];
