<?php
/**
 * @package modx
 * @subpackage processors.element.snippet
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('snippet');

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'name';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

$c = $modx->newQuery('modSnippet');
$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);

if (isset($_REQUEST['limit'])) {
    $c = $c->limit($_REQUEST['limit'],$_REQUEST['start']);
}
$snippets = $modx->getCollection('modSnippet',$c);
$count = $modx->getCount('modSnippet');

$cs = array();
foreach ($snippets as $snippet) {
    $cs[] = $snippet->toArray();
}

return $this->outputArray($cs,$count);