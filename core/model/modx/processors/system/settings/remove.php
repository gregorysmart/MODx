<?php
/**
 * @package modx
 * @subpackage processors.system.settings
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('system_setting');

if (!isset($_POST['key'])) $modx->error->failure($modx->lexicon('setting_err_ns'));
$setting = $modx->getObject('modSystemSetting',$_POST['key']);
if ($setting == null) $modx->error->failure($modx->lexicon('setting_err_nf'));

if ($setting->remove() == null) {
    $modx->error->failure($modx->lexicon('setting_err_remove'));
}

$modx->reloadConfig();

$modx->error->success();