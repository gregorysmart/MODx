<?php
/**
 * @package modx
 * @subpackage processors.element.chunk
 */
$modx->lexicon->load('chunk');

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'name';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

$c = $modx->newQuery('modChunk');
$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);

if (isset($_REQUEST['limit'])) {
    $c->limit($_REQUEST['limit'],$_REQUEST['start']);
}
$chunks = $modx->getCollection('modChunk',$c);
$count = $modx->getCount('modChunk');

$cs = array();
foreach ($chunks as $chunk) {
    $cs[] = $chunk->toArray();
}

return $this->outputArray($cs,$count);