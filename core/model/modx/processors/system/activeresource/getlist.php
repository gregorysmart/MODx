<?php
/**
 * @package modx
 * @subpackage processors.system.activeresource
 */

require_once MODX_PROCESSORS_PATH.'index.php';

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;

$c = $modx->newQuery('modResource');
$c->where(array('deleted' => 0));
$c->sortby('editedon','DESC');
$c->limit($_REQUEST['limit'],$_REQUEST['start']);
$resources = $modx->getCollection('modResource',$c);

$cc = $modx->newQuery('modResource');
$cc->where(array('deleted' => 0));
$total = $modx->getCount('modResource',$cc);

$rs = array();
foreach ($resources as $resource) {
	$editor = $modx->getObject('modUser',$resource->editedby);
	$r = $resource->get(array_diff(array_keys($resource->_fields), array('content')));
	$r['editedon'] = strftime('%x %X',$r['editedon']);
	$r['user'] = $editor->username;
	$rs[] = $r;
}

$this->outputArray($rs,$total);