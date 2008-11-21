<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modEventLog']= array (
  'package' => 'modx',
  'table' => 'event_log',
  'fields' => 
  array (
    'eventid' => 0,
    'createdon' => 0,
    'type' => 1,
    'user' => 0,
    'usertype' => 0,
    'source' => '',
    'description' => NULL,
  ),
  'fieldMeta' => 
  array (
    'eventid' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => true,
      'default' => 0,
    ),
    'createdon' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'timestamp',
      'null' => false,
      'default' => 0,
    ),
    'type' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '4',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 1,
    ),
    'user' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'usertype' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '4',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'source' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '50',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'description' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
    ),
  ),
  'composites' => 
  array (
    'modEvent' => 
    array (
      'class' => 'modEvent',
      'key' => 'id',
      'local' => 'eventid',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
if (XPDO_PHP4_MODE) $xpdo_meta_map['modEventLog']['composites']= array_merge($xpdo_meta_map['modEventLog']['composites'], array_change_key_case($xpdo_meta_map['modEventLog']['composites']));
$xpdo_meta_map['modeventlog']= & $xpdo_meta_map['modEventLog'];
