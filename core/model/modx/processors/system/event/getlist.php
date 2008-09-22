<?php
/**
 * @package modx
 * @subpackage processors.system.event
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('system_event');

if (!$modx->hasPermission('view_eventlog')) $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'name';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

$c = $modx->newQuery('modEvent');
$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);
$c->limit($_REQUEST['limit'],$_REQUEST['start']);
$events = $modx->getCollection('modEvent',$c);

$count = $modx->getCount('modEvent');

$ss = array();
foreach ($events as $event) {
    $sa = $event->toArray();
    
    $ss[] = $sa;
}
$this->outputArray($ss,$count);