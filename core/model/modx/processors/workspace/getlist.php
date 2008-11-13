<?php
/**
 * @package modx
 * @subpackage processors.workspace
 */
$modx->lexicon->load('workspace');

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'name';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

$c = $modx->newQuery('modWorkspace');
$workspaces = $modx->getCollection('modWorkspace',$c);
$count = $modx->getCount('modWorkspace');

$ws = array();
foreach ($workspaces as $workspace) {
    $wa = $workspace->toArray();
    $ws[] = $wa;
}
return $this->outputArray($ws,$count);