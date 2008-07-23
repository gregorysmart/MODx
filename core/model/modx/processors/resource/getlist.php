<?php
/**
 * @package modx
 * @subpackage processors.resource
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('resource');

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'pagetitle';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

$c = $modx->newQuery('modResource');
$c = $c->sortby($_REQUEST['sort'],$_REQUEST['dir']);

if (isset($_REQUEST['limit'])) {
    $c = $c->limit($_REQUEST['limit'],$_REQUEST['start']);
}
$resources = $modx->getCollection('modResource',$c);
$count = $modx->getCount('modResource');
$cs = array();
foreach ($resources as $resource) {
    $cs[] = $resource->toArray();
}
$this->outputArray($cs,$count);