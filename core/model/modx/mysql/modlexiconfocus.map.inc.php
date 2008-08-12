<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modLexiconFocus']= array (
  'package' => 'modx',
  'table' => 'lexicon_foci',
  'fields' => 
  array (
    'name' => '',
    'namespace' => 'core',
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
      'index' => 'pk',
    ),
    'namespace' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '40',
      'phptype' => 'string',
      'null' => 'false',
      'default' => 'core',
      'index' => 'pk',
    ),
  ),
  'composites' => 
  array (
    'modLexiconEntry' => 
    array (
      'class' => 'modLexiconEntry',
      'local' => 'name',
      'foreign' => 'focus',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
  'aggregates' => 
  array (
    'modNamespace' => 
    array (
      'class' => 'modNamespace',
      'local' => 'namespace',
      'foreign' => 'key',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
if (XPDO_PHP4_MODE) $xpdo_meta_map['modLexiconFocus']['aggregates']= array_merge($xpdo_meta_map['modLexiconFocus']['aggregates'], array_change_key_case($xpdo_meta_map['modLexiconFocus']['aggregates']));
if (XPDO_PHP4_MODE) $xpdo_meta_map['modLexiconFocus']['composites']= array_merge($xpdo_meta_map['modLexiconFocus']['composites'], array_change_key_case($xpdo_meta_map['modLexiconFocus']['composites']));
$xpdo_meta_map['modlexiconfocus']= & $xpdo_meta_map['modLexiconFocus'];
