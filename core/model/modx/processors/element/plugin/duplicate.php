<?php
/**
 * @package modx
 * @subpackage processors.element.plugin
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('plugin');

if (!$modx->hasPermission('new_plugin')) $modx->error->failure($modx->lexicon('permission_denied'));

// get old snippet
$old_plugin = $modx->getObject('modPlugin',$_REQUEST['id']);
if ($old_plugin == null) $modx->error->failure($modx->lexicon('plugin_err_not_found'));

$newname = isset($_POST['name']) 
    ? $_POST['name']
    : $modx->lexicon('duplicate_of').$old_plugin->name;

$plugin = $modx->newObject('modPlugin');
$plugin->set('name',$newname);
$plugin->set('description',$old_plugin->description);
$plugin->set('plugincode',$old_plugin->plugincode);
$plugin->set('moduleguid',$old_plugin->moduleguid);
$plugin->set('locked',$old_plugin->locked);
$plugin->set('properties',$old_plugin->properties);
$plugin->set('category',$old_plugin->category);

if ($plugin->save() === false) {
    $modx->error->failure($modx->lexicon('plugin_err_save'));
}

// duplicate events
$old_plugin->events = $old_plugin->getMany('modPluginEvent');
foreach($old_plugin->events as $old_event) {
	$new_event = $modx->newObject('modPluginEvent');
	$new_event->set('pluginid',$plugin->id);
	$new_event->set('evtid',$old_event->evtid);
	$new_event->set('priority',$old_event->priority);
	if (!$new_event->save())
		$modx->error->failure($modx->lexicon('plugin_event_err_duplicate'));
}

// log manager action
$modx->logManagerAction('duplicate_plugin','modPlugin',$plugin->id);

$modx->error->success('',$plugin->get(array_diff(array_keys($plugin->_fields), array('plugincode'))));