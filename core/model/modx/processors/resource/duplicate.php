<?php
/**
 * @package modx
 * @subpackage processors.resource
 */

require_once MODX_PROCESSORS_PATH.'index.php';

$duplicate_children = isset($_POST['duplicate_children']);
$newname = isset($_POST['name']) && $_POST['name'] != '' ? $_POST['name'] : '';

// get user id for createdby column
$user_id = $modx->getLoginUserID();

// get document
$old_document = $modx->getObject('modResource',$_REQUEST['id']);
if ($old_document == NULL) $error->failure($_lang['document_not_found']);

if (!$modx->hasPermission('new_document'))
    $error->failure($modx->lexicon('permission_denied'));
if (!$old_document->checkPolicy('copy'))
    $error->failure($modx->lexicon('permission_denied'));

// get parent
$parent = $old_document->getOne('Parent');
if ($parent && !$parent->checkPolicy('add_children'))
    $error->failure($modx->lexicon('permission_denied'));

// get document's children
$old_document->children = getChildren($old_document);

$newdocid = $_REQUEST['id'];
$new_document = duplicateDocument($old_document,$newname,$duplicate_children);


function duplicateDocument($document,$newname = '',$duplicate_children = true,$_toplevel = 0) {
	global $modx;
	global $error;
	global $user_id;

	if ($newname == '') $newname = 'Duplicate of '.$document->pagetitle;
	// duplicate document
	$new_document = $modx->newObject($document->_class);
	$new_document->fromArray($document->toArray('', true), '', false, true);
	$new_document->set('pagetitle',$newname);
	$new_document->set('alias', null);
	$new_document->set('parent',$_toplevel == 0 ? $document->parent : $_toplevel); //make sure children get assigned to new parent
	$new_document->set('createdby',$user_id);
	$new_document->set('createdon',time());
	$new_document->set('editedby',0);
	$new_document->set('editedon',0);
	$new_document->set('deleted',0);
	$new_document->set('deletedon',0);
	$new_document->set('deletedby',0);
	$new_document->set('publishedon',0);
	$new_document->set('publishedby',0);
	$new_document->set('published',false);
	if (!$new_document->save())
		$error->failure('An error occurred while duplicating the document.');

	if($_toplevel==0) {
		global $newdocid;
		$newdocid = $new_document->get('id');
	}

	if($_toplevel==0) {
		global $newdocid;
		$newdocid = $new_document->id;
	}

	// duplicate document TVDs
	$document->tvds = $document->getMany('modTemplateVarResource');
	foreach ($document->tvds as $old_tvd) {
		$new_tvd = $modx->newObject('modTemplateVarResource');
		$new_tvd->set('contentid',$new_document->id);
		$new_tvd->set('tmplvarid',$old_tvd->tmplvarid);
		$new_tvd->set('value',$old_tvd->value);
		if (!$new_tvd->save())
			$error->failure('An error occurred while duplicating template variables.');
	}

	// duplicate document keywords
	$document->keywords = $document->getMany('modResourceKeyword');
	foreach ($document->keywords as $old_kw) {
		$new_kw = $modx->newObject('modResourceKeyword');
		$new_kw->set('content_id',$new_document->id);
		$new_kw->set('keyword_id',$old_kw->keyword_id);
		if (!$new_kw->save())
			$error->failure('An error occurred while duplicating document keywords.');
	}

	// duplicate document groups
	$document->groups = $document->getMany('modResourceGroupResource');
	foreach ($document->groups as $old_group) {
		$new_group = $modx->newObject('modResourceGroupResource');
		$new_group->set('document_group',$old_group->document_group);
		$new_group->set('document',$new_document->id);
		if (!$new_group->save())
			$error->failure('An error occurred while duplicating document groups.');
	}

	// duplicate document metatags
	$document->metatags = $document->getMany('modResourceMetatag');
	foreach ($document->metatags as $old_mt) {
		$new_mt = $modx->newObject('modResourceMetatag');
		$new_mt->set('content_id',$new_document->id);
		$new_mt->set('metatag_id',$old_mt->id);
		if (!$new_mt->save()) {
			$error->failure('An error occurred while duplicating document metatags.');
	}
	}

	// duplicate children, recursively
	if ($duplicate_children && count($document->children) > 0) {
		foreach ($document->children as $child) {
			duplicateDocument($child,'',true,$new_document->id);
		}
	}
	return $new_document;
}

// Get Children
function getChildren($parent) {
	global $modx;
	$children = $parent->getMany('Children');
	if (count($children) > 0) {
		foreach ($children as $child) {
			$child->children = getChildren($child);
		}
	}
	return $children;
}

// log manager action
$modx->logManagerAction('delete_resource','modDocument',$document->id);

$error->success('', array ('id' => $new_document->get('id')));