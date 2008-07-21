<?php
/**
 * @package modx
 * @subpackage processors.element.plugin
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('plugin','category');

if (!$modx->hasPermission('new_plugin')) $error->failure($modx->lexicon('permission_denied'));

if ($_POST['name'] == '') $_POST['name'] = $modx->lexicon('plugin_untitled');

$name_exists = $modx->getObject('modPlugin',array('name' => $_POST['name']));
if ($name_exists != null) $error->addField('name',$modx->lexicon('plugin_err_exists_name'));

if ($error->hasError()) $error->failure();


// category
if (is_numeric($_POST['category'])) {
    $category = $modx->getObject('modCategory',array('id' => $_POST['category']));
} else {
    $category = $modx->getObject('modCategory',array('category' => $_POST['category']));
}
if ($category == null) {
	$category = $modx->newObject('modCategory');
	if ($_POST['category'] == '' || $_POST['category'] == 'null') {
		$category->id = 0;
	} else {
		$category->set('category',$_POST['category']);
		if (!$category->save()) $error->failure($modx->lexicon('category_err_save'));
	}
}

// invoke OnBeforePluginFormSave event
$modx->invokeEvent('OnBeforePluginFormSave',array(
	'mode' => 'new',
	'id' => 0
));

$plugin = $modx->newObject('modPlugin');
$plugin->fromArray($_POST);
$plugin->set('locked',isset($_POST['locked']));
$plugin->set('category',$category->id);

if (!$plugin->save()) $error->failure($modx->lexicon('plugin_err_create'));

// change system events
if (isset($_POST['events'])) {
    $_EVENTS = $modx->fromJSON($_POST['events']);
    foreach ($_EVENTS as $id => $event) {
        if ($event['enabled']) {
            $pe = $modx->getObject('modPluginEvent',array(
                'pluginid' => $plugin->id,
                'evtid' => $event['id'],
            ));
            if ($pe == null) {
                $pe = $modx->newObject('modPluginEvent');
            }
            $pe->set('pluginid',$plugin->id);
            $pe->set('evtid',$event['id']);
            $pe->set('priority',$event['priority']);
            $pe->save();
        } else {
            $pe = $modx->getObject('modPluginEvent',array(
                'pluginid' => $plugin->id,
                'evtid' => $event['id'],
            ));
            if ($pe == null) continue;
            $pe->remove();
        }
    }
}


// invoke OnPluginFormSave event
$modx->invokeEvent('OnPluginFormSave',array(
	'mode' => 'new',
	'id' => $plugin->id,
));

// log manager action
$modx->logManagerAction('new_plugin','modPlugin',$plugin->id);

// empty cache
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

$error->success('',$plugin->get(array_diff(array_keys($plugin->_fields), array('plugincode'))));