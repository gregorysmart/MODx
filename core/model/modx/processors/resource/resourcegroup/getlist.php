<?php
/**
 * @package modx
 * @subpackage processors.resource.resourcegroup
 */

require_once MODX_PROCESSORS_PATH.'index.php';

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'name';

$c = $modx->newQuery('modResourceGroup');
$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);
$c->limit($_REQUEST['limit'],$_REQUEST['start']);
$rgs = $modx->getCollection('modResourceGroup',$c);

$count = $modx->getCount('modResourceGroup');

$rs = array();
foreach ($rgs as $rg) {
    $ra = $rg->toArray();
    
    $rgr = $rg->getOne('modResourceGroupResource',array(
        'document' => $_REQUEST['resource'],
    ));
    $ra['access'] = $rgr != null;
    
    $rs[] = $ra;
}

$this->outputArray($rs,$count);