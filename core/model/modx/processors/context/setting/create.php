<?php
/**
 * @package modx
 * @subpackage processors.context.setting
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('system_setting');

$_POST['context_key'] = isset($_POST['fk']) ? $_POST['fk'] : 0;

$ae = $modx->getObject('modSystemSetting',array(
    'key' => $_POST['key'],
    'context_key' => $_POST['context_key'],
));
if ($ae != null) $modx->error->failure($modx->lexicon('setting_err_ae'));

$setting= $modx->newObject('modContextSetting');
$setting->fromArray($_POST,'',true);

if ($setting->save() === false) {
    $modx->error->checkValidation($setting);
    $modx->error->failure($modx->lexicon('setting_err_save'));
}

$modx->reloadConfig();

$modx->error->success();