<?php
/**
 * @package modx
 * @subpackage processors.system.menu
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('action','menu');
if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'menuindex';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

$c = $modx->newQuery('modMenu');
$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);
//$c->limit($_REQUEST['limit'],$_REQUEST['start']);
$menus = $modx->getCollection('modMenu',$c);

$count = $modx->getCount('modMenu');

$ms = array();

foreach ($menus as $menu) {
	$ma = $menu->toArray();
    $ma['text'] = $modx->lexicon($ma['text']);
    $ms[] = $ma;
}
$this->outputArray($ms,$count);