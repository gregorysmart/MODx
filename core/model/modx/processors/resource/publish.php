<?php
/**
 * @package modx
 * @subpackage processors.resource
 */
global $document;

require_once MODX_PROCESSORS_PATH.'index.php';

$user_id = $modx->getLoginUserID();

// get document
$document = $modx->getObject('modResource',$_REQUEST['id']);
if ($document == NULL) $error->failure($modx->lexicon('document_not_found'));

if (!$modx->hasPermission('publish_document')) $error->failure($modx->lexicon('permission_denied'));

// check permissions on the document
if (!$document->checkPolicy(array('save'=>1, 'publish'=>1)))
    $error->failure($modx->lexicon('permission_denied'));

//include_once MODX_CORE_PATH.'model/modx/udperms.class.php';
//$udperms = new udperms();
//$udperms->user = $user_id;
//$udperms->document = $document->id;
//$udperms->role = $_SESSION['mgrRole'];
//
//if(!$udperms->checkPermissions())
//	$error->failure($modx->lexicon('access_permission_denied'));

// publish document
$document->set('published',1);
$document->set('pub_date',0);
$document->set('unpub_date',0);
$document->set('editedby',$user_id);
$document->set('editedon',time());
$document->set('publishedby',$user_id);
$document->set('publishedon',time());

if (!$document->save()) $error->failure($modx->lexicon('document_err_publish'));

// invoke OnDocPublished event
$modx->invokeEvent('OnDocPublished',array('docid' => $document->id));

// empty the cache
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

// log manager action
$modx->logManagerAction('publish_resource','modDocument',$document->id);

$error->success('',$document->get(array('id', 'pub_date', 'unpub_date', 'editedby', 'editedon', 'publishedby', 'publishedon')));