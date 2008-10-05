<?php
/**
 * @package modx
 * @subpackage processors.system.settings
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('setting');
if (!$modx->hasPermission('settings')) $error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['key'])) $modx->error->failure($modx->lexicon('setting_err_ns'));
$setting = $modx->getObject('modSystemSetting',$_POST['key']);
if ($setting == null) $modx->error->failure($modx->lexicon('setting_err_nf'));

// remove relative lexicon strings
$entry = $modx->getObject('modLexiconEntry',array(
    'namespace' => $setting->get('namespace'),
    'name' => 'setting_'.$setting->get('key'),
));
if ($entry != null) $entry->remove();

$description = $modx->getObject('modLexiconEntry',array(
    'namespace' => $setting->get('namespace'),
    'name' => 'setting_'.$setting->get('key').'_desc',
));
if ($description != null) $description->remove();

if ($setting->remove() == null) {
    $modx->error->failure($modx->lexicon('setting_err_remove'));
}

$modx->reloadConfig();

$modx->error->success();