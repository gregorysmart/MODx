<?php
/**
 * @package modx
 * @subpackage processors.system.settings
 */
$modx->lexicon->load('setting');

if (!$modx->hasPermission('settings')) return $modx->error->failure($modx->lexicon('permission_denied'));

$_DATA = $modx->fromJSON($_POST['data']);

$setting = $modx->getObject('modSystemSetting',array(
    'key' => $_DATA['key'],
));

/* set new value */
$setting->set('value',$_DATA['value']);
$setting->set('area',$_DATA['area']);

/* if name changed, change lexicon string */
$entry = $modx->getObject('modLexiconEntry',array(
    'namespace' => $setting->get('namespace'),
    'name' => 'setting_'.$_DATA['oldkey'],
));
if ($entry != null) {
    $entry->set('value',$_DATA['name']);
    $entry->save();
    $entry->clearCache();
}

if ($setting->save() == false) {
    return $modx->error->failure($modx->lexicon('setting_err_save'));
}


$modx->reloadConfig();

return $modx->error->success();