<?php
/**
 * @package modx
 * @subpackage processors.resource
 */
global $document;

require_once MODX_PROCESSORS_PATH.'index.php';

$user_id = $modx->getLoginUserID();

$document = $modx->getObject('modResource',$_REQUEST['id']);
if ($document == null) $error->failure($modx->lexicon('document_not_found'));

//if (!$modx->hasPermission('publish_document')) $error->failure($modx->lexicon('permission_denied'));

// check permissions on the document
if (!$document->checkPolicy(array('save'=>1, 'publish'=>1)))
    $error->failure($modx->lexicon('permission_denied'));

//include_once MODX_CORE_PATH.'model/modx/udperms.class.php';
//$udperms = new udperms();
//$udperms->user = $user_id;
//$udperms->document = $_REQUEST['id'];
//$udperms->role = $_SESSION['mgrRole'];
//
//if (!$udperms->checkPermissions())
//	$error->failure($modx->lexicon('access_permission_denied'));

// update the document
$document->set('published',0);
$document->set('pub_date',0);
$document->set('unpub_date',0);
$document->set('editedby',$user_id);
$document->set('editedon',time());
$document->set('publishedby',0);
$document->set('publishedon',0);
if (!$document->save())
	$error->failure($modx->lexicon('document_err_unpublish'));

// invoke OnDocUnpublished  event
$modx->invokeEvent('OnDocUnpublished',array('docid' => $document->id));

// log manager action
$modx->logManagerAction('unpublish_resource','modDocument',$document->id);

// empty the cache
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

$error->success('',$document->get(array('id')));
