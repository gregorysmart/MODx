<?php
/**
 * Grabs a list of modCategories.
 *
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to category.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @package modx
 * @subpackage processors.element.category
 */
$modx->lexicon->load('category');

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
/* if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 20; */
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'category';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

$c = $modx->newQuery('modCategory');
$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);

if (isset($_REQUEST['limit'])) {
	$c->limit($_REQUEST['limit'],$_REQUEST['start']);
}
$categories = $modx->getCollection('modCategory',$c);
$count = $modx->getCount('modCategory');
$cs = array('0' => array(
    'id' => '',
    'category' => $modx->lexicon('none'),
));

foreach ($categories as $category) {
	$cs[] = $category->toArray();
}

return $this->outputArray($cs,$count);