<?php
/**
 * @package modx
 * @subpackage processors.system.log
 */

require_once MODX_PROCESSORS_PATH.'index.php';
if (!$modx->hasPermission('logs')) $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'occurred';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

$wa = array();
if (isset($_POST['action_type']) && $_POST['action_type'] != '') {
    $wa['action:LIKE'] = '%'.$_POST['action_type'].'%';
}
if (isset($_POST['user']) && $_POST['user'] != '') {
    $wa['user'] = $_POST['user'];
}
if (isset($_POST['date_start']) && $_POST['date_start'] != '') {
    $_POST['date_start'] = strftime('%Y-%m-%d',strtotime($_POST['date_start'].' 00:00:00'));
    $wa['occurred:>='] = $_POST['date_start'];
}
if (isset($_POST['date_end']) && $_POST['date_end'] != '') {
    $_POST['date_end'] = strftime('%Y-%m-%d',strtotime($_POST['date_end'].' 23:59:59'));
    $wa['occurred:<='] = $_POST['date_end'];
}

$c = $modx->newQuery('modManagerLog');
if (!empty($wa)) $c->where($wa);
$c->sortby($_REQUEST['sort'], $_REQUEST['dir']);
$c->limit($_REQUEST['limit'], $_REQUEST['start']);

$cc = $modx->newQuery('modManagerLog');
if (!empty($wa)) $c->where($wa);
$logs = $modx->getCollection('modManagerLog',$c);
$count = $modx->getCount('modManagerLog',$cc);

$ls = array();
foreach ($logs as $log) {
    $la = $log->toArray();
    $la['occurred'] = strftime('%a %b %d, %Y %H:%I:%S %p',strtotime($la['occurred']));
    
    
    
    $ls[] = $la;
}
$this->outputArray($ls,$count);