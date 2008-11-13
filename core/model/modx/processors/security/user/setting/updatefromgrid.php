<?php
/**
 * @package modx
 * @subpackage processors.security.user.setting
 */
$modx->lexicon->load('setting');

$_DATA = $modx->fromJSON($_POST['data']);

$setting = $modx->getObject('modUserSetting',array(
    'key' => $_DATA['key'],
    'user' => $_DATA['user'],
));

$setting->set('value',$_DATA['value']);

if ($setting->save() == false) {
    return $modx->error->failure($modx->lexicon('setting_err_save'));
}

$modx->reloadConfig();

return $modx->error->success();