<?php
/**
 * @package modx
 * @subpackage processors.system.settings
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('system_setting');

$ae = $modx->getObject('modSystemSetting',array(
    'key' => $_POST['key'],
));
if ($ae != null) $modx->error->failure($modx->lexicon('setting_err_ae'));

$setting= $modx->newObject('modSystemSetting');
$setting->fromArray($_POST,'',true);

if ($setting->save() === false) {
    $modx->error->checkValidation($setting);
    $modx->error->failure($modx->lexicon('setting_err_save'));
}

$modx->reloadConfig();

$modx->error->success();