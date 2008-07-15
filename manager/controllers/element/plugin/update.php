<?php
/**
 * Load update plugin page 
 * 
 * @package modx
 * @subpackage manager.element.plugin
 */
if (!$modx->hasPermission('edit_plugin')) $modx->error->failure($modx->lexicon('access_denied'));

// load plugin
$plugin = $modx->getObject('modPlugin',$_REQUEST['id']);
if ($plugin == null) $modx->error->failure($modx->lexicon('plugin_not_found'));
$plugin->category = $plugin->getOne('modCategory');

// invoke OnPluginFormPrerender event
$onPluginFormPrerender = $modx->invokeEvent('OnPluginFormPrerender',array('id' => $_REQUEST['id']));
if (is_array($onPluginFormPrerender)) $onPluginFormPrerender = implode('',$onPluginFormPrerender);
$modx->smarty->assign('onPluginFormPrerender',$onPluginFormPrerender);

// invoke OnPluginFormRender event
$onPluginFormRender = $modx->invokeEvent('OnPluginFormRender',array('id' => $_REQUEST['id']));
if (is_array($onPluginFormRender)) $onPluginFormRender = implode('',$onPluginFormRender);
$modx->smarty->assign('onPluginFormRender',$onPluginFormRender);

// display system events
// TODO: i18n system event group names
$evtnames = array();
$services = array(
	array('name' => 'Parser Service Events','events'=> array()),
	array('name' => 'Manager Access Events','events'=> array()),
	array('name' => 'Web Access Service Events','events'=> array()),
	array('name' => 'Cache Service Events','events'=> array()),
	array('name' => 'Template Service Events','events'=> array()),
	array('name' => 'User Defined Events','events'=> array()),
);

$l = count($services);
$evts = array();
for ($i=0;$i<$l;$i++) {
	$service = &$services[$i];
	$c = $modx->newQuery('modEvent');
	$c = $c->where(array('service' => $i+1)); 
	$c = $c->sortby('groupname,name');
	$service['events'] = $modx->getCollection('modEvent',$c);
}

// check selected event
$events = $plugin->getMany('modPluginEvent');
$evts = array();
foreach ($events as $event) {
	$evts[] = $event->evtid;
}

// load events and services into parser
$modx->smarty->assign('evts',$evts);
$modx->smarty->assign('services',$services);

// load plugin into parser and display
$modx->smarty->assign('plugin',$plugin);
$modx->smarty->display('element/plugin/update.tpl');
