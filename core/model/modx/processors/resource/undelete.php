<?php
/**
 * @package modx
 * @subpackage processors.resource
 */
$modx->lexicon->load('resource');

$resource = $modx->getObject('modResource',$_REQUEST['id']);
if ($resource == null) return $modx->error->failure($modx->lexicon('resource_err_nfs',array('id' => $_REQUEST['id'])));

/* check permissions on the resource */
if (!$resource->checkPolicy(array('save'=>1, 'undelete'=>1))) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}

$deltime = $resource->get('deletedon');

function getChildren($parent) {
	global $modx;
	global $deltime;

	$kids = $modx->getCollection('modResource',array(
		'parent' => $parent,
		'deleted' => 1,
		'deletedon' => $deltime,
	));

	if(count($kids) > 0) {
		/* the resource has children resources, we'll need to undelete those too */
		foreach ($kids as $kid) {
			$kid->set('deleted',0);
			$kid->set('deletedby',0);
			$kid->set('deletedon',0);
			if (!$kid->save()) {
				return $modx->error->failure($modx->lexicon('resource_err_undelete_children'));
            }
			getChildren($kid->get('id'));
		}
	}
}

getChildren($resource->get('id'));
/* 'undelete' the resource. */

$resource->set('deleted',0);
$resource->set('deletedby',0);
$resource->set('deletedon',0);

if ($resource->save() == false) {
    return $modx->error->failure($modx->lexicon('resource_err_undelete'));
}

/* log manager action */
$modx->logManagerAction('undelete_resource','modResource',$resource->get('id'));

/* empty cache */
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

return $modx->error->success('',$resource->get(array('id')));