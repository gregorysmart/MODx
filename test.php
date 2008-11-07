<?php
require_once('_build/build.config.php');
require_once('core/model/modx/modx.class.php');

$modx= new modX();

$modx->initialize('mgr');

$modx->setLogTarget('ECHO');
//$modx->setDebug(true);

$object = $modx->getObject('modSystemSetting', array('key' => 'captcha_words', 'namespace' => 'captcha'));
print_r($object->toArray());
exit();