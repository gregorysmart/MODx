<?php
$collection['1']= $xpdo->newObject('modAccessContext');
$collection['1']->fromArray(array (
  'id' => 1,
  'target' => 'mgr',
  'principal_class' => 'modUserGroup',
  'principal' => 1,
  'authority' => 9999,
  'policy' => 2,
), '', true, true);
$collection['2']= $xpdo->newObject('modAccessContext');
$collection['2']->fromArray(array (
  'id' => 2,
  'target' => 'connector',
  'principal_class' => 'modUserGroup',
  'principal' => 1,
  'authority' => 9999,
  'policy' => 2,
), '', true, true);
