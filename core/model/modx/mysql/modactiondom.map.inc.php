<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modActionDom']= array (
  'package' => 'modx',
  'table' => 'actiondom',
  'fields' => 
  array (
    'action' => 0,
    'name' => '',
    'description' => NULL,
    'xtype' => '',
    'container' => '',
    'rule' => '',
    'value' => '',
    'constraint' => '',
    'constraint_field' => '',
    'constraint_class' => '',
  ),
  'fieldMeta' => 
  array (
    'action' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'index',
    ),
    'description' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
    ),
    'xtype' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'container' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'rule' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'value' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'constraint' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'constraint_field' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'constraint_class' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
  ),
  'aggregates' => 
  array (
    'Action' => 
    array (
      'class' => 'modAction',
      'local' => 'action',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
  'composites' => 
  array (
    'Access' => 
    array (
      'class' => 'modAccessActionDom',
      'local' => 'id',
      'foreign' => 'target',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
if (XPDO_PHP4_MODE) $xpdo_meta_map['modActionDom']['aggregates']= array_merge($xpdo_meta_map['modActionDom']['aggregates'], array_change_key_case($xpdo_meta_map['modActionDom']['aggregates']));
if (XPDO_PHP4_MODE) $xpdo_meta_map['modActionDom']['composites']= array_merge($xpdo_meta_map['modActionDom']['composites'], array_change_key_case($xpdo_meta_map['modActionDom']['composites']));
$xpdo_meta_map['modactiondom']= & $xpdo_meta_map['modActionDom'];
