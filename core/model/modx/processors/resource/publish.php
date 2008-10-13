<?php
/**
 * @package modx
 * @subpackage processors.resource
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('resource');

/* get resource */
$resource = $modx->getObject('modResource',$_REQUEST['id']);
if ($resource == null) $modx->error->failure($modx->lexicon('resource_err_nfs',array('id' => $_REQUEST['id'])));

if (!$modx->hasPermission('publish_document')) {
    $modx->error->failure($modx->lexicon('permission_denied'));
}

/* check permissions on the resource */
if (!$resource->checkPolicy(array('save'=>1, 'publish'=>1))) {
    $modx->error->failure($modx->lexicon('permission_denied'));
}

/* publish resource */
$resource->set('published',1);
$resource->set('pub_date',0);
$resource->set('unpub_date',0);
$resource->set('editedby',$modx->user->get('id'));
$resource->set('editedon',time());
$resource->set('publishedby',$modx->user->get('id'));
$resource->set('publishedon',time());

if (!$resource->save()) $modx->error->failure($modx->lexicon('resource_err_publish'));

/* invoke OnDocPublished event */
$modx->invokeEvent('OnDocPublished',array(
    'docid' => $resource->get('id'),
    'resource' => $resource->get('id'),
));

/* empty the cache */
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

/* log manager action */
$modx->logManagerAction('publish_resource','modResource',$resource->get('id'));

$error->success('',$resource->get(array('id', 'pub_date', 'unpub_date', 'editedby', 'editedon', 'publishedby', 'publishedon')));