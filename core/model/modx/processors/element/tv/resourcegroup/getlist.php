<?php
/**
 * @package modx
 * @subpackage processors.element.template.tv.resourcegroup
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('tv');

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 20;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'name';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

if (isset($_REQUEST['tv'])) {
    $tv = $modx->getObject('modTemplateVar',$_REQUEST['tv']);
    if ($tv == null) $modx->error->failure($modx->lexicon('tv_err_nf'));
}

$c = $modx->newQuery('modResourceGroup');
$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);
if (isset($_REQUEST['limit'])) {
    $c->limit($_REQUEST['limit'],$_REQUEST['start']);
}
$groups = $modx->getCollection('modResourceGroup',$c);
$count = $modx->getCount('modResourceGroup');

$ts = array();
foreach ($groups as $group) {
    if (isset($_REQUEST['tv'])) {
        $rgtv = $modx->getObject('modTemplateVarResourceGroup',array(
            'tmplvarid' => $tv->get('id'),
            'documentgroup' => $group->get('id'),
        ));
    } else $rgtv = null;

    if ($rgtv != null) {
        $group->set('access',true);
    } else {
        $group->set('access',false);
    }
    $ta = $group->toArray();
    $ta['menu'] = array(
    );
    $ts[] = $ta;
}

$this->outputArray($ts,$count);