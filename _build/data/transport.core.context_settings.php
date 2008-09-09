<?php
$collection['connector-modRequest.class']= $xpdo->newObject('modContextSetting');
$collection['connector-modRequest.class']->fromArray(array (
  'context_key' => 'connector',
  'key' => 'modRequest.class',
  'value' => 'modConnectorRequest',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'system',
), '', true, true);
$collection['mgr-allow_tags_in_post']= $xpdo->newObject('modContextSetting');
$collection['mgr-allow_tags_in_post']->fromArray(array (
  'context_key' => 'mgr',
  'key' => 'allow_tags_in_post',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'system',
), '', true, true);
$collection['mgr-modRequest.class']= $xpdo->newObject('modContextSetting');
$collection['mgr-modRequest.class']->fromArray(array (
  'context_key' => 'mgr',
  'key' => 'modRequest.class',
  'value' => 'modManagerRequest',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'system',
), '', true, true);
