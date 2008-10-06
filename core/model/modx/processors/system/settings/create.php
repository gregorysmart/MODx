<?php
/**
 * @package modx
 * @subpackage processors.system.settings
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('setting','namespace');
if (!$modx->hasPermission('settings')) $error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['namespace'])) $modx->error->failure($modx->lexicon('namespace_err_ns'));
$namespace = $modx->getObject('modNamespace',$_POST['namespace']);
if ($namespace == null) $modx->error->failure($modx->lexicon('namespace_err_nf'));

$ae = $modx->getObject('modSystemSetting',array(
    'key' => $_POST['key'],
));
if ($ae != null) $modx->error->failure($modx->lexicon('setting_err_ae'));

// value parsing
if ($_POST['xtype'] == 'combo-boolean' && !is_numeric($_POST['value'])) {
	if ($_POST['value'] == 'yes' || $_POST['value'] == 'Yes' || $_POST['value'] == $modx->lexicon('yes')) {
		$_POST['value'] = 1;
	} else $_POST['value'] = 0;
}

$setting= $modx->newObject('modSystemSetting');
$setting->fromArray($_POST,'',true);

// set lexicon name/description
$topic = $modx->getObject('modLexiconTopic',array(
    'name' => 'default',
    'namespace' => $setting->namespace,
));
if ($topic == null) {
    $topic = $modx->newObject('modLexiconTopic');
    $topic->set('name','default');
    $topic->set('namespace',$setting->namespace);
    $topic->save();
}


$entry = $modx->getObject('modLexiconEntry',array(
    'namespace' => $namespace->name,
    'name' => 'setting_'.$_POST['key'],
));
if ($entry == null) {
    $entry = $modx->newObject('modLexiconEntry');
    $entry->set('namespace',$namespace->name);
    $entry->set('name','setting_'.$_POST['key']);
    $entry->set('value',$_POST['name']);
    $entry->set('topic',$topic->get('id'));
    $entry->set('language',$modx->cultureKey);
    $entry->save();

    $entry->clearCache();
}
$description = $modx->getObject('modLexiconEntry',array(
    'namespace' => $namespace->name,
    'name' => 'setting_'.$_POST['key'].'_desc',
));
if ($description == null) {
    $description = $modx->newObject('modLexiconEntry');
    $description->set('namespace',$namespace->name);
    $description->set('name','setting_'.$_POST['key'].'_desc');
	$description->set('value',$_POST['description']);
    $description->set('topic',$topic->get('id'));
    $description->set('language',$modx->cultureKey);
    $description->save();

    $description->clearCache();
}

if ($setting->save() === false) {
    $modx->error->checkValidation($setting);
    $modx->error->failure($modx->lexicon('setting_err_save'));
}


$modx->reloadConfig();

$modx->error->success();