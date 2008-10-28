<?php
/**
 * @package modx
 * @subpackage processors.element.plugin.event
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('plugin','system_events');
if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'name';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

$wa = array();
if (isset($_POST['name']) && $_POST['name'] != '') {
    $wa = array(
        'name:LIKE' => '%'.$_POST['name'].'%',
    );
}


$c = $modx->newQuery('modEvent');
$c->where($wa);
$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);
$c->limit($_REQUEST['limit'],$_REQUEST['start']);
$events = $modx->getCollection('modEvent',$c);


$cc = $modx->newQuery('modEvent');
$cc->where($wa);
$count = $modx->getCount('modEvent',$cc);

$es = array();
foreach ($events as $event) {
    $ea = $event->toArray();
    
    if (isset($_REQUEST['id'])) {
        $pe = $modx->getObject('modPluginEvent',array(
            'pluginid' => $_REQUEST['id'],
            'evtid' => $event->id,
        ));
    } else $pe = null;
    $ea['enabled'] = $pe != null;
    $ea['priority'] = $pe == null ? '' : $pe->get('priority');
    
    $es[] = $ea;
}
return $this->outputArray($es,$count);