<?php
/**
 * @package modx
 * @subpackage processors.resource
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('resource');

$resource = $modx->getObject('modResource',$_REQUEST['id']);
if ($resource == null) $modx->error->failure($modx->lexicon('resource_err_nfs',array('id' => $_REQUEST['id'])));

if (!$modx->hasPermission('publish_document')) {
    $modx->error->failure($modx->lexicon('permission_denied'));
}

// check permissions on the resource
if (!$resource->checkPolicy(array('save'=>1, 'unpublish'=>1))) {
    $modx->error->failure($modx->lexicon('permission_denied'));
}

// update the resource
$resource->set('published',0);
$resource->set('pub_date',0);
$resource->set('unpub_date',0);
$resource->set('editedby',$user_id);
$resource->set('editedon',time());
$resource->set('publishedby',0);
$resource->set('publishedon',0);
if ($resource->save() == false) {
	$modx->error->failure($modx->lexicon('resource_err_unpublish'));
}

// invoke OnDocUnpublished event
$modx->invokeEvent('OnDocUnpublished',array('docid' => $resource->id));

// log manager action
$modx->logManagerAction('unpublish_resource','modResource',$resource->id);

// empty the cache
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

$modx->error->success('',$resource->get(array('id')));
