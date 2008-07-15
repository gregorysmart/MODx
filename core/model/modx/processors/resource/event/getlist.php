<?php
/**
 * @package modx
 * @subpackage processors.resource
 */

require_once MODX_PROCESSORS_PATH.'index.php';

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';
if (!isset($_REQUEST['mode'])) $_REQUEST['mode'] = 'pub_date';

$c = $modx->newQuery('modResource');
$c->where(array(
    $_REQUEST['mode'].':>' => time(),
));
$c->sortby($_REQUEST['mode'],$_REQUEST['dir']);
$c->limit($_REQUEST['limit'],$_REQUEST['start']);
$publish = $modx->getCollection('modResource',$c);

$cc = $modx->newQuery('modResource');
$cc->where(array(
    $_REQUEST['mode'].':>' => time(),
));
$count = $modx->getCollection('modResource',$cc);

$ps = array();
$time_format = '%a %b %d, %Y';
foreach ($publish as $resource) {
    $pa = $resource->toArray();
    
    if ($resource->get('pub_date') != '') {
        $pd = $resource->get('pub_date')+$modx->config['server_offset_time'];
        $pa['pub_date'] = strftime($time_format,$pd);
    }
    
    if ($resource->get('unpub_date') != '') {
        $pd = $resource->get('unpub_date')+$modx->config['server_offset_time'];
        $pa['unpub_date'] = strftime($time_format,$pd);
    }
    $ps[] = $pa;
}

$this->outputArray($ps,$count);