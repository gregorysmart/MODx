<?php
/**
 * @package modx
 * @subpackage processors.element.tv
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('tv');

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'name';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

$c = $modx->newQuery('modTemplateVar');
$c = $c->sortby($_REQUEST['sort'],$_REQUEST['dir']);

if (isset($_REQUEST['limit'])) {
    $c = $c->limit($_REQUEST['limit'],$_REQUEST['start']);
}
$tvs = $modx->getCollection('modTemplateVar',$c);
$count = $modx->getCount('modTemplateVar');

$cs = array();
foreach ($tvs as $tv) {
    $cs[] = $tv->toArray();
}

$this->outputArray($cs,$count);