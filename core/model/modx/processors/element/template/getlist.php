<?php
/**
 * @package modx
 * @subpackage processors.element.template
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('template');

//if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
//if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 20;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'templatename';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

$c = $modx->newQuery('modTemplate');
$c = $c->sortby($_REQUEST['sort'],$_REQUEST['dir']);

if (isset($_REQUEST['limit'])) {
	$c = $c->limit($_REQUEST['limit'],$_REQUEST['start']);
}

$templates = $modx->getCollection('modTemplate',$c);
$count = $modx->getCount('modTemplate');
$cs = array();
if (isset($_REQUEST['combo'])) {
    $empty = array(
        'id' => 0,
        'templatename' => '(empty)',
        'description' => '',
        'editor_type' => 0,
        'icon' => '',
        'template_type' => 0,
        'content' => '',
        'locked' => false,
    );
    $empty['category'] = '';
    $cs[] = $empty;
}
foreach ($templates as $template) {
	$cat = $template->getOne('modCategory');
	$ca = $template->toArray();
	$ca['category'] = $cat ? $cat->category : '';
	$cs[] = $ca;
}
$this->outputArray($cs,$count);