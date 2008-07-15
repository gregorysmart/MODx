<?php
/**
 * @package modx
 * @subpackage processors.system.contenttype
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('content_type');
if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'name';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

$c = $modx->newQuery('modContentType');
$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);
$c->limit($_REQUEST['limit'],$_REQUEST['start']);
$types = $modx->getCollection('modContentType',$c);

$count = $modx->getCount('modContentType');

$cts = array();
foreach ($types as $type) {
    $cta = $type->toArray();
    $cta['menu'] = array(
        array(
            'text' => $modx->lexicon('content_type_remove'),
            'handler' => 'this.confirm.createDelegate(this,["remove","'.$modx->lexicon('content_type_remove_confirm').'"])'
        )
    );
    $cts[] = $cta;
}
$this->outputArray($cts,$count);