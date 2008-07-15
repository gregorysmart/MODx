<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modActiveUser']= array (
  'package' => 'modx',
  'table' => 'active_users',
  'fields' => 
  array (
    'internalKey' => '0',
    'username' => '',
    'lasthit' => '0',
    'id' => NULL,
    'action' => '',
    'ip' => '',
  ),
  'fieldMeta' => 
  array (
    'internalKey' => 
    array (
      'dbtype' => 'int',
      'precision' => '9',
      'phptype' => 'integer',
      'null' => 'false',
      'default' => '0',
      'index' => 'pk',
    ),
    'username' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '50',
      'phptype' => 'string',
      'null' => 'false',
      'default' => '',
    ),
    'lasthit' => 
    array (
      'dbtype' => 'int',
      'precision' => '20',
      'phptype' => 'timestamp',
      'null' => 'false',
      'default' => '0',
    ),
    'id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => 'true',
    ),
    'action' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => 'false',
      'default' => '',
    ),
    'ip' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '20',
      'phptype' => 'string',
      'null' => 'false',
      'default' => '',
    ),
  ),
  'aggregates' => 
  array (
    'modUser' => 
    array (
      'class' => 'modUser',
      'local' => 'internalKey',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
if (XPDO_PHP4_MODE) $xpdo_meta_map['modActiveUser']['aggregates']= array_merge($xpdo_meta_map['modActiveUser']['aggregates'], array_change_key_case($xpdo_meta_map['modActiveUser']['aggregates']));
$xpdo_meta_map['modactiveuser']= & $xpdo_meta_map['modActiveUser'];
