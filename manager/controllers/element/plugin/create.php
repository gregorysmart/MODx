<?php
/**
 * Load create plugin page 
 * 
 * @package modx
 * @subpackage manager.element.plugin
 */
if (!$modx->hasPermission('new_plugin')) $modx->error->failure($modx->lexicon('access_denied'));

// grab category if preset
if (isset($_REQUEST['category'])) {
	$category = $modx->getObject('modCategory',$_REQUEST['category']);
	if ($category != null) $modx->smarty->assign('category',$category);
}

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
for ($i=0;$i<$l;$i++) {
	$service = &$services[$i];
	$c = $modx->newQuery('modEvent');
	$c = $c->where(array('service' => $i+1)); 
	$c = $c->sortby('groupname,name');
	$service['events'] = $modx->getCollection('modEvent',$c);
}

// load events and services into parser
$modx->smarty->assign('evts',array());
$modx->smarty->assign('services',$services);


$modx->smarty->display('element/plugin/create.tpl');