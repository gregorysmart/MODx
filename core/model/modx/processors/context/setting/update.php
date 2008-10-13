<?php
/**
 * @package modx
 * @subpackage processors.security.user.setting
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('setting');

$context = $modx->getObject('modContext', $_POST['context_key']);
if ($context == null) $modx->error->failure($modx->lexicon('setting_err_nf'));

if (!$context->checkPolicy('save')) $modx->error->failure($modx->lexicon('permission_denied'));

$setting = $modx->getObject('modContextSetting',array(
    'key' => $_POST['key'],
    'context_key' => $_POST['context_key'],
));
$setting->set('value',$_POST['value']);

if ($setting->save() == false) {
    $modx->error->failure($modx->lexicon('setting_err_save'));
}

$modx->reloadConfig();

$modx->error->success();