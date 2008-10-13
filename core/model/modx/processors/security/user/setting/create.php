<?php
/**
 * @package modx
 * @subpackage processors.context.setting
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('setting');

$_POST['user'] = isset($_POST['fk']) ? $_POST['fk'] : 0;

$ae = $modx->getObject('modUserSetting',array(
    'key' => $_POST['key'],
    'user' => $_POST['user'],
));
if ($ae != null) $modx->error->failure($modx->lexicon('setting_err_ae'));

$setting= $modx->newObject('modUserSetting');
$setting->fromArray($_POST,'',true);


/* set lexicon name/description */
$topic = $modx->getObject('modLexiconTopic',array(
    'name' => 'default',
    'namespace' => $setting->get('namespace'),
));
if ($topic == null) {
    $topic = $modx->newObject('modLexiconTopic');
    $topic->set('name','default');
    $topic->set('namespace',$setting->get('namespace'));
    $topic->save();
}

$entry = $modx->getObject('modLexiconEntry',array(
    'namespace' => $namespace->get('name'),
    'name' => 'setting_'.$_POST['key'],
));
if ($entry == null) {
    $entry = $modx->newObject('modLexiconEntry');
    $entry->set('namespace',$namespace->get('name'));
    $entry->set('name','setting_'.$_POST['key']);
    $entry->set('value',$_POST['name']);
    $entry->set('topic',$topic->get('id'));
    $entry->save();
}
$description = $modx->getObject('modLexiconEntry',array(
    'namespace' => $namespace->get('name'),
    'name' => 'setting_'.$_POST['key'].'_desc',
));
if ($description == null) {
    $description = $modx->newObject('modLexiconEntry');
    $description->set('namespace',$namespace->get('name'));
    $description->set('name','setting_'.$_POST['key'].'_desc');
    $description->set('value',$_POST['description']);
    $description->set('topic',$topic->get('id'));
    $description->save();
}

if ($setting->save() === false) {
    $modx->error->checkValidation($setting);
    $modx->error->failure($modx->lexicon('setting_err_save'));
}

$modx->reloadConfig();

$modx->error->success();