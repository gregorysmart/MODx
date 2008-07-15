<?php
/**
 * @package modx
 * @subpackage processors.security.user.setting
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('context_setting','system_setting');

$_DATA = $modx->fromJSON($_POST['data']);

$setting = $modx->getObject('modContextSetting',array(
    'key' => $_DATA['key'],
    'context_key' => $_DATA['context_key'],
));
$setting->set('value',$_DATA['value']);
if ($setting->save() == false) {
    $modx->error->failure($modx->lexicon('setting_err_save'));
}

$modx->reloadConfig();

$modx->error->success();