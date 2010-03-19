<?php
/**
 * Create a user setting
 *
 * @param integer $user/$fk The user to create the setting for
 * @param string $key The setting key
 * @param string $value The value of the setting
 * @param string $name The lexicon name for the setting
 * @param string $description The lexicon description for the setting
 * @param string $area The area for the setting
 * @param string $namespace The namespace for the setting
 *
 * @package modx
 * @subpackage processors.context.setting
 */
if (!$modx->hasPermission('settings')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('setting','namespace');

/* get namespace */
if (empty($scriptProperties['namespace'])) $modx->error->addField('namespace',$modx->lexicon('namespace_err_ns'));
$namespace = $modx->getObject('modNamespace',$scriptProperties['namespace']);
if ($namespace == null) $modx->error->addField('namespace',$modx->lexicon('namespace_err_nf'));

/* prevent keys starting with numbers */
$nums = explode(',','1,2,3,4,5,6,7,8,9,0');
if (in_array(substr($scriptProperties['key'],0,1),$nums)) {
    $modx->error->addField('key',$modx->lexicon('setting_err_startint'));
}

/* prevent duplicate keys */
$alreadyExists = $modx->getObject('modUserSetting',array(
    'key' => $scriptProperties['key'],
    'user' => $scriptProperties['user'],
));
if ($alreadyExists) return $modx->error->failure($modx->lexicon('setting_err_ae'));


if ($modx->error->hasError) {
    return $modx->error->failure();
}



$scriptProperties['user'] = isset($scriptProperties['fk']) ? $scriptProperties['fk'] : 0;

$setting= $modx->newObject('modUserSetting');
$setting->fromArray($scriptProperties,'',true);


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


/* only set name/description lexicon entries if they dont exist
 * for user settings
 */
$entry = $modx->getObject('modLexiconEntry',array(
    'namespace' => $namespace->get('name'),
    'name' => 'setting_'.$scriptProperties['key'],
));
if ($entry == null) {
    $entry = $modx->newObject('modLexiconEntry');
    $entry->set('namespace',$namespace->get('name'));
    $entry->set('name','setting_'.$scriptProperties['key']);
    $entry->set('value',$scriptProperties['name']);
    $entry->set('topic',$topic->get('id'));
    $entry->save();
}
$description = $modx->getObject('modLexiconEntry',array(
    'namespace' => $namespace->get('name'),
    'name' => 'setting_'.$scriptProperties['key'].'_desc',
));
if ($description == null) {
    $description = $modx->newObject('modLexiconEntry');
    $description->set('namespace',$namespace->get('name'));
    $description->set('name','setting_'.$scriptProperties['key'].'_desc');
    $description->set('value',$scriptProperties['description']);
    $description->set('topic',$topic->get('id'));
    $description->save();
}

/* save setting */
if ($setting->save() === false) {
    $modx->error->checkValidation($setting);
    return $modx->error->failure($modx->lexicon('setting_err_save'));
}

$modx->reloadConfig();

return $modx->error->success('',$setting);