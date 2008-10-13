<?php
/**
 * @package modx
 * @subpackage processors.resource
 * @deprecated
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('resource');

if ($_REQUEST['id'] == $_REQUEST['new_parent']) {
	$modx->error->failure($modx->lexicon('resource_err_own_parent'));
}

if ($_REQUEST['id'] == '') {
	$modx->error->failure($modx->lexicon('resource_err_ns'));
}

if ($_REQUEST['new_parent'] == '') {
	$modx->error->failure($modx->lexicon('resource_err_select_parent'));
}

$resource = $modx->getObject('modResource',$_REQUEST['id']);
if ($resource == null) $modx->error->failure($modx->lexicon('resource_err_nfs',array('id' => $_REQUEST['id'])));

$parent = $modx->getObject('modResource',$_REQUEST['new_parent']);
if ($parent == null && $parent != 0) $modx->error->failure($modx->lexicon('resource_err_new_parent_nf',array('id' => $_REQUEST['new_parent'])));


$oldparent = $resource->get('parent');

/* check user has permission to move resource to chosen location */
if (!$resource->checkPolicy(array('save'=>1,'move'=>1)) || !$parent->checkPolicy('add_children')) {
    $modx->error->failure($modx->lexicon('permission_denied'));
}

function allChildren($currResID,$children = array()) {
	global $modx;
	$kids = $modx->getCollection('modResource',array('parent' => $currResID));

	foreach ($kids as $kid) {
		$children[] = $kid->get('id');
		$nextgen = allChildren($kid->get('id'));
		$children = array_merge($children,$nextgen);
	}
	return $children;
}

$children = allChildren($resource->get('id'));

if (array_search($_REQUEST['new_parent'], $children)) {
	$modx->error->failure($modx->lexicon('resource_err_move_to_child'));
}

if ($parent != 0) {
	$parent->set('isfolder',1);
	$parent->save();
}

$resource->set('parent',$_REQUEST['new_parent']);
$resource->set('editedby',$modx->user->get('id'));
$resource->set('editedon',time());
$resource->save();

/* finished moving the resource, now check to see if the old_parent should no longer be a folder. */
if ($oldparent != 0) {
	$kids_count = $modx->getCount('modResource',array('parent' => $oldparent));
	if ($kids_count == 0) {
		$oldparent = $modx->getObject('modResource',$oldparent);
		$oldparent->set('isfolder',0);
		$oldparent->save();
	}
}

/* empty cache & sync site */
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();


$modx->error->success();