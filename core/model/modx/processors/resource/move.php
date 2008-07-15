<?php
/**
 * @package modx
 * @subpackage processors.resource
 */

require_once MODX_PROCESSORS_PATH.'index.php';

$document = $modx->getObject('modResource',$_REQUEST['id']);
if ($document == null) $error->failure($modx->lexicon('document_not_found'));


if ($_REQUEST['id'] == $_REQUEST['new_parent'])
	$error->failure($modx->lexicon('document_own_parent_error'));

if ($_REQUEST['id'] == '')
	$error->failure($modx->lexicon('document_id_not_specified'));

if ($_REQUEST['new_parent'] == '')
	$error->failure($modx->lexicon('document_select_parent_error'));

$document = $modx->getObject('modResource',$_REQUEST['id']);
if ($document == NULL) $error->failure(sprintf($modx->lexicon('document_with_id_not_found'),$_REQUEST['id']));

$parent = $modx->getObject('modResource',$_REQUEST['new_parent']);
if ($parent == NULL && $parent != 0) $error->failure(sprintf($modx->lexicon('parent_with_id_not_specified'),$_REQUEST['new_parent']));


//if ($_REQUEST['new_parent'] == $document->parent)
//	die($error->process('That document is already its parent.'));

$oldparent = $document->parent;

// check user has permission to move document to chosen location
if (!$document->checkPolicy(array('save'=>1,'move'=>1)) || !$parent->checkPolicy(array('save'=>1,'add_children'=>1)))
    $error->failure($modx->lexicon('permission_denied'));

//if ($modx->config['use_udperms'] == 1) {
//	include_once MODX_CORE_PATH.'model/modx/udperms.class.php';
//	$udperms = new udperms();
//	$udperms->user = $modx->getLoginUserID();
//	$udperms->document = $_REQUEST['new_parent'];
//	$udperms->role = $_SESSION['mgrRole'];
//
//	if (!$udperms->checkPermissions())
//		$error->failure($modx->lexicon('access_permission_parent_denied']);
//}

function allChildren($currDocID,$children = array()) {
	global $modx;
	$kids = $modx->getCollection('modResource',array('parent' => $currDocID));

	foreach ($kids as $kid) {
		$children[] = $kid->id;
		$nextgen = allChildren($kid->id);
		$children = array_merge($children,$nextgen);
	}
	return $children;
}

$children = allChildren($document->id);

if (array_search($_REQUEST['new_parent'], $children))
	$error->failure($modx->lexicon('move_document_to_child_error'));

if ($parent != 0) {
	$parent->set('isfolder',1);
	$parent->save();
}

$document->set('parent',$_REQUEST['new_parent']);
$document->set('editedby',$modx->getLoginUserID());
$document->set('editedon',time());
$document->save();

// finished moving the document, now check to see if the old_parent should no longer be a folder.
if ($oldparent != 0) {
	$kids_count = $modx->getCount('modResource',array('parent' => $oldparent));
	if ($kids_count == 0) {
		$oldparent = $modx->getObject('modResource',$oldparent);
		$oldparent->set('isfolder',0);
		$oldparent->save();
	}
}

// empty cache & sync site
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();


$error->success();