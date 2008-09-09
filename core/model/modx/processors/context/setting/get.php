<?php
/**
 * @package modx
 * @subpackage processors.context.setting
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('setting');

if (!isset($_POST['key'],$_POST['context_key'])) $modx->error->failure($modx->lexicon('setting_err_ns'));
$setting = $modx->getObject('modContextSetting',array(
    'key' => $_POST['key'],
    'context_key' => $_POST['context_key'],
));
if ($setting == null) $modx->error->failure($modx->lexicon('setting_err_nf'));

$modx->error->success('',$setting);