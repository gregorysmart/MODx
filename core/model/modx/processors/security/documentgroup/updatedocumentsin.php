<?php
/**
 * @package modx
 * @subpackage processors.security.documentgroup
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('resource');

if (!$modx->hasPermission('access_permissions')) $modx->error->failure($modx->lexicon('permission_denied'));

// format data
$_POST['resource'] = substr(strrchr($_POST['resource'],'_'),1);
$_POST['resource_group'] = substr(strrchr($_POST['resource_group'],'_'),1);

if ($_POST['resource'] == 0 || $_POST['resource_group'] == 0) $error->failure('Invalid data.');

// get resource
$resource = $modx->getObject('modResource',$_POST['resource']);
if ($resource == null) $modx->error->failure($modx->lexicon('resource_err_nfs',array('id' => $_POST['resource'])));

// get resource group
$resourceGroup = $modx->getObject('modResourceGroup',$_POST['resource_group']);
if ($resourceGroup == null) $modx->error->failure($modx->lexicon('document_group_err_not_specified'));

// check to make sure already isnt in group
$rgr = $modx->getObject('modResourceGroupResource',array(
	'document' => $resource->id,
	'document_group' => $resourceGroup->id,
));
if ($rgr != null) $modx->error->failure($modx->lexicon('document_group_document_err_already_exists'));

// create resource group -> resource pairing
$rgr = $modx->newObject('modResourceGroupResource');
$rgr->set('document',$resource->id);
$rgr->set('document_group',$resourceGroup->id);
if (!$rgr->save()) $modx->error->failure($modx->lexicon('document_group_document_err_create'));

$modx->error->success();