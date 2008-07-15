<?php
/**
 * @package modx
 * @subpackage processors.resource.resourcegroup
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('resource');

$_DATA = $modx->fromJSON($_POST['data']);

if (!isset($_DATA['resource'])) $modx->error->failure($modx->lexicon('resource_err_ns'));
$resource = $modx->getObject('modResource',$_DATA['resource']);
if ($resource == null) $modx->error->failure($modx->lexicon('resource_err_nf'));

if (!isset($_DATA['id'])) $modx->error->failure($modx->lexicon('resource_group_err_ns'));
$rg = $modx->getObject('modResourceGroup',$_DATA['id']);
if ($rg == null) $modx->error->failure($modx->lexicon('resource_group_err_nf'));

$rgr = $modx->getObject('modResourceGroupResource',array(
    'document' => $resource->id,
    'document_group' => $rg->id,
));

if ($_DATA['access'] == true && $rgr != null) {
    $modx->error->failure($modx->lexicon('resource_group_resource_err_ae'));
}
if ($_DATA['access'] == false && $rgr == null) {
    $modx->error->failure($modx->lexicon('resource_group_resource_err_nf'));
}
if ($_DATA['access'] == true) {
    $rgr = $modx->newObject('modResourceGroupResource');
    $rgr->set('document',$resource->id);
    $rgr->set('document_group',$rg->id);
    $rgr->save();
} else {
    $rgr->remove();
}

$modx->error->success();