<?php
/**
 * @package modx
 * @subpackage processors.resource
 *
 * @param integer $start The page to start on
 * @param integer $limit (optional) The number of results to limit by
 * @param string $sort The column to sort by
 * @param string $dir The direction to sort
 * @return array An array of modResources
 */
$modx->lexicon->load('resource');

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'pagetitle';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

$c = $modx->newQuery('modResource');
$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);

if (isset($_REQUEST['limit'])) {
    $c->limit($_REQUEST['limit'],$_REQUEST['start']);
}
$resources = $modx->getCollection('modResource',$c);

$cs = array();
foreach ($resources as $resource) {
    if ($resource->checkPolicy('list')) {
        $cs[] = $resource->toArray();
    }
}
return $this->outputArray($cs);