<?php
/**
 * @package modx
 * @subpackage processors.security.user.setting
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('setting');

$_DATA = $modx->fromJSON($_POST['data']);

if (!$context = $modx->getObject('modContext', $_DATA['context_key'])) $modx->error->failure($modx->lexicon('setting_err_nf'));
if (!$context->checkPolicy('save')) $modx->error->failure($modx->lexicon('permission_denied'));

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