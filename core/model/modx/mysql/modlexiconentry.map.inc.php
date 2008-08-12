<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modLexiconEntry']= array (
  'package' => 'modx',
  'table' => 'lexicon_entries',
  'fields' => 
  array (
    'name' => '',
    'value' => '',
    'focus' => 'default',
    'namespace' => 'core',
    'language' => 'en',
    'createdon' => NULL,
    'editedon' => NULL,
  ),
  'fieldMeta' => 
  array (
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => 'false',
      'default' => '',
    ),
    'value' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => 'false',
      'default' => '',
    ),
    'focus' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => 'false',
      'default' => 'default',
    ),
    'namespace' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '40',
      'phptype' => 'string',
      'null' => 'false',
      'default' => 'core',
    ),
    'language' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '20',
      'phptype' => 'string',
      'null' => 'false',
      'default' => 'en',
    ),
    'createdon' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => 'false',
    ),
    'editedon' => 
    array (
      'dbtype' => 'timestamp',
      'phptype' => 'timestamp',
      'null' => 'false',
      'attributes' => 'ON UPDATE CURRENT_TIMESTAMP',
    ),
  ),
  'aggregates' => 
  array (
    'modNamespace' => 
    array (
      'class' => 'modNamespace',
      'local' => 'namespace',
      'foreign' => 'name',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'modLexiconFocus' => 
    array (
      'class' => 'modLexiconFocus',
      'local' => 'focus',
      'foreign' => 'name',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'modLexiconLanguage' => 
    array (
      'class' => 'modLexiconLanguage',
      'local' => 'language',
      'foreign' => 'name',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
if (XPDO_PHP4_MODE) $xpdo_meta_map['modLexiconEntry']['aggregates']= array_merge($xpdo_meta_map['modLexiconEntry']['aggregates'], array_change_key_case($xpdo_meta_map['modLexiconEntry']['aggregates']));
$xpdo_meta_map['modlexiconentry']= & $xpdo_meta_map['modLexiconEntry'];
