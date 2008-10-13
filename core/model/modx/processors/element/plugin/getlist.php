<?php
/**
 * @package modx
 * @subpackage processors.element.plugin
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('plugin');

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'name';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

$c = $modx->newQuery('modPlugin');
$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);
if (isset($_REQUEST['limit'])) {
    $c->limit($_REQUEST['limit'],$_REQUEST['start']);
}

$plugins = $modx->getCollection('modPlugin',$c);
$count = $modx->getCount('modPlugin');

$cs = array();
foreach ($plugins as $plugin) {
    $cs[] = $plugin->toArray();
}

$this->outputArray($cs,$count);