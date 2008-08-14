<?php
/**
 * @package modx
 * @subpackage processors.security.user.setting
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('user_setting','system_setting');

if (!isset($_POST['key'],$_POST['user'])) $modx->error->failure($modx->lexicon('setting_err_ns'));
$setting = $modx->getObject('modUserSetting',array(
    'key' => $_POST['key'],
    'user' => $_POST['user'],
));
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