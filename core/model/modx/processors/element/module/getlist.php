<?php
/**
 * @package modx
 * @subpackage processors.element.module
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('module');

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'name';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'DESC';
if ($_REQUEST['sort'] == 'module_link') $_REQUEST['sort'] = 'name';
$c = $modx->newQuery('modModule');
$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);
$c->limit($_REQUEST['limit'],$_REQUEST['start']);
$modules = $modx->getCollection('modModule', $c);

$actions = $modx->request->getAllActionIDs();
$count = $modx->getCount('modModule');

$md = array();
foreach ($modules as $m) {
	$ma = $m->toArray();
	$ma['name'] = '<a href="index.php?a='.$actions['element/module/update'].'&id='.$ma['id'].'">'.$ma['name'].'</a>';
	//$ma['icon'] = $ma['icon'] !='' ? '<a href="index.php?a=112&id='.$ma['id'].'"><img src="'.$ma['icon'].' alt="" /></a>' : '<a href="index.php?a=112&id='.$ma['id'].'"><img src="media/style/'.$modx->config['manager_theme'].'/images/icons/module.gif" alt="" /></a>';
    
    $ma['menu'] = array(
        array(
            'text' => $modx->lexicon('module_run'),
            'handler' => 'this.run',
        ),
        '-',
        array(
            'text' => $modx->lexicon('update'),
            'handler' => 'this.update',
        ),
        array(
            'text' => $modx->lexicon('duplicate'),
            'handler' => 'this.confirm.createDelegate(this,["duplicate","module_duplicate_confirm"])',
        ),
        '-',
        array(
            'text' => $modx->lexicon('remove'),
            'handler' => 'this.remove.createDelegate(this,["module_delete_confirm"])',
        ),
    );
	$md[] = $ma;
}
$this->outputArray($md,$count);