<?php
/**
 * Duplicates a resource, and optionally, all of its children.
 *
 * @param integer $id The ID of the resource.
 * @param string $name The new name of the resource that will be created.
 * @param boolean $duplicate_children (optional) If true, will duplicate the
 * resource's children as well. Defaults to false.
 * @return array An array of values of the new resource.
 *
 * @package modx
 * @subpackage processors.resource
 */
$modx->lexicon->load('resource');

$duplicate_children = isset($_POST['duplicate_children']);
$newname = isset($_POST['name']) && $_POST['name'] != '' ? $_POST['name'] : '';

/* get resource */
$old_resource = $modx->getObject('modResource',$_REQUEST['id']);
if ($old_resource == null) return $modx->error->failure($modx->lexicon('resource_err_nfs',array('id' => $_REQUEST['id'])));

if (!$modx->hasPermission('new_document')) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}
if (!$old_resource->checkPolicy('copy'))
    return $modx->error->failure($modx->lexicon('permission_denied'));

/* get parent */
$parent = $old_resource->getOne('Parent');
if ($parent && !$parent->checkPolicy('add_children')) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}

/* get resource's children */
$old_resource->children = getChildren($old_resource);

$new_id = $_REQUEST['id'];
$new_resource = duplicateResource($old_resource,$newname,$duplicate_children);


function duplicateResource($resource,$newname = '',$duplicate_children = true,$_toplevel = 0) {
	global $modx;

	if ($newname == '') $newname = $modx->lexicon('duplicate_of').$resource->pagetitle;
	/* duplicate resource */
	$new_resource = $modx->newObject($resource->_class);
	$new_resource->fromArray($resource->toArray('', true), '', false, true);
	$new_resource->set('pagetitle',$newname);
	$new_resource->set('alias', null);
    /* make sure children get assigned to new parent */
	$new_resource->set('parent',$_toplevel == 0 ? $resource->get('parent') : $_toplevel);
	$new_resource->set('createdby',$modx->user->get('id'));
	$new_resource->set('createdon',time());
	$new_resource->set('editedby',0);
	$new_resource->set('editedon',0);
	$new_resource->set('deleted',0);
	$new_resource->set('deletedon',0);
	$new_resource->set('deletedby',0);
	$new_resource->set('publishedon',0);
	$new_resource->set('publishedby',0);
	$new_resource->set('published',false);
	if (!$new_resource->save()) {
		return $modx->error->failure($modx->lexicon('resource_err_duplicate'));
    }

	if($_toplevel==0) {
		global $new_id;
		$new_id = $new_resource->get('id');
	}

	/* duplicate resource TVs */
	$resource->tvds = $resource->getMany('modTemplateVarResource');
	foreach ($resource->tvds as $old_tvd) {
		$new_tvd = $modx->newObject('modTemplateVarResource');
		$new_tvd->set('contentid',$new_resource->get('id'));
		$new_tvd->set('tmplvarid',$old_tvd->get('tmplvarid'));
		$new_tvd->set('value',$old_tvd->get('value'));
		$new_tvd->save();
	}

	/* duplicate resource keywords */
	$resource->keywords = $resource->getMany('modResourceKeyword');
	foreach ($resource->keywords as $old_kw) {
		$new_kw = $modx->newObject('modResourceKeyword');
		$new_kw->set('content_id',$new_resource->get('id'));
		$new_kw->set('keyword_id',$old_kw->get('keyword_id'));
		$new_kw->save();
	}

	/* duplicate resource groups */
	$resource->groups = $resource->getMany('modResourceGroupResource');
	foreach ($resource->groups as $old_group) {
		$new_group = $modx->newObject('modResourceGroupResource');
		$new_group->set('document_group',$old_group->get('document_group'));
		$new_group->set('document',$new_resource->get('id'));
		$new_group->save();
	}

	/* duplicate resource metatags */
	$resource->metatags = $resource->getMany('modResourceMetatag');
	foreach ($resource->metatags as $old_mt) {
		$new_mt = $modx->newObject('modResourceMetatag');
		$new_mt->set('content_id',$new_resource->get('id'));
		$new_mt->set('metatag_id',$old_mt->get('id'));
		$new_mt->save();
	}

	/* duplicate resource, recursively */
	if ($duplicate_children && count($resource->children) > 0) {
		foreach ($resource->children as $child) {
			duplicateDocument($child,'',true,$new_resource->get('id'));
		}
	}
	return $new_resource;
}

/* Get Children */
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

/* log manager action */
$modx->logManagerAction('delete_resource','modResource',$new_resource->get('id'));

return $modx->error->success('', array ('id' => $new_resource->get('id')));