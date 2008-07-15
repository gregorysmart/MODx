<?php
/**
 * @package modx
 * @subpackage processors.context.setting
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('user_setting','system_setting');

$_POST['user'] = isset($_POST['fk']) ? $_POST['fk'] : 0;

$ae = $modx->getObject('modUserSetting',array(
    'key' => $_POST['key'],
    'user' => $_POST['user'],
));
if ($ae != null) $modx->error->failure($modx->lexicon('setting_err_ae'));

$setting= $modx->newObject('modUserSetting');
$setting->fromArray($_POST,'',true);

if ($setting->save() === false) {
    $modx->error->checkValidation($setting);
    $modx->error->failure($modx->lexicon('setting_err_save'));
}

$modx->reloadConfig();

$modx->error->success();