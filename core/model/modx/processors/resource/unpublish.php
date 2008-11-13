<?php
/**
 * @package modx
 * @subpackage processors.resource
 */
$modx->lexicon->load('resource');

$resource = $modx->getObject('modResource',$_REQUEST['id']);
if ($resource == null) return $modx->error->failure($modx->lexicon('resource_err_nfs',array('id' => $_REQUEST['id'])));

if (!$modx->hasPermission('publish_document')) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}

/* check permissions on the resource */
if (!$resource->checkPolicy(array('save'=>1, 'unpublish'=>1))) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}

/* update the resource */
$resource->set('published',0);
$resource->set('pub_date',0);
$resource->set('unpub_date',0);
$resource->set('editedby',$modx->user->get('id'));
$resource->set('editedon',time());
$resource->set('publishedby',0);
$resource->set('publishedon',0);
if ($resource->save() == false) {
	return $modx->error->failure($modx->lexicon('resource_err_unpublish'));
}

/* invoke OnDocUnpublished event */
$modx->invokeEvent('OnDocUnpublished',array('docid' => $resource->get('id')));

/* log manager action */
$modx->logManagerAction('unpublish_resource','modResource',$resource->get('id'));

/* empty the cache */
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

return $modx->error->success('',$resource->get(array('id')));