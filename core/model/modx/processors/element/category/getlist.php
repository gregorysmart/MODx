<?php
/**
 * @package modx
 * @subpackage processors.element.category
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('category');

/* if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0; */
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