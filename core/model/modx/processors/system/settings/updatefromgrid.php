<?php
/**
 * @package modx
 * @subpackage processors.system.settings
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('system_setting');

$_DATA = $modx->fromJSON($_POST['data']);

$setting = $modx->getObject('modSystemSetting',array(
    'key' => $_DATA['key'],
));
$setting->set('value',$_DATA['value']);
if ($setting->save() == false) {
    $modx->error->failure($modx->lexicon('setting_err_save'));
}

$modx->reloadConfig();

$modx->error->success();