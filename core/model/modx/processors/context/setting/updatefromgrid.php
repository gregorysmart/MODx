<?php
/**
 * @package modx
 * @subpackage processors.context.setting
 */
$modx->lexicon->load('setting');

$_DATA = $modx->fromJSON($_POST['data']);

if (!$context = $modx->getObject('modContext', $_DATA['context_key'])) return $modx->error->failure($modx->lexicon('setting_err_nf'));
if (!$context->checkPolicy('save')) return $modx->error->failure($modx->lexicon('permission_denied'));

$setting = $modx->getObject('modContextSetting',array(
    'key' => $_DATA['key'],
    'context_key' => $_DATA['context_key'],
));
$setting->set('value',$_DATA['value']);
if ($setting->save() == false) {
    return $modx->error->failure($modx->lexicon('setting_err_save'));
}

$modx->reloadConfig();

return $modx->error->success();