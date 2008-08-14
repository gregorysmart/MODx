<?php
/**
 * @package modx
 * @subpackage processors.system.settings
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('system_setting');

$_DATA = $modx->fromJSON($_POST['data']);

//$modx->error->failure(print_r($_DATA,true));

$setting = $modx->getObject('modSystemSetting',array(
    'key' => $_DATA['key'],
));

// set new value
$setting->set('value',$_DATA['value']);

// if name changed, change lexicon string
$entry = $modx->getObject('modLexiconEntry',array(
    'namespace' => 'core',
    'name' => 'setting_'.$_DATA['oldkey'],
));
if ($entry != null) {
    $entry->set('value',$_DATA['name']);
    $entry->save();
    $r = $modx->lexicon->clearCache($entry->get('language').'/'.$entry->get('namespace').'/'.$entry->get('focus').'.cache.php');
}

if ($setting->save() == false) {
    $modx->error->failure($modx->lexicon('setting_err_save'));
}


$modx->reloadConfig();

$modx->error->success();