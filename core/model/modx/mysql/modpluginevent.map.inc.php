<?php
/**
 * @package modx
 * @subpackage mysql
 */
$xpdo_meta_map['modPluginEvent']= array (
  'package' => 'modx',
  'table' => 'site_plugin_events',
  'fields' => 
  array (
    'pluginid' => 0,
    'evtid' => 0,
    'priority' => 0,
    'propertyset' => 0,
  ),
  'fieldMeta' => 
  array (
    'pluginid' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'pk',
    ),
    'evtid' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'pk',
    ),
    'priority' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'propertyset' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
  ),
  'aggregates' => 
  array (
    'Plugin' => 
    array (
      'class' => 'modPlugin',
      'local' => 'pluginid',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Event' => 
    array (
      'class' => 'modEvent',
      'local' => 'evtid',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'PropertySet' => 
    array (
      'class' => 'modPropertySet',
      'local' => 'propertyset',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
