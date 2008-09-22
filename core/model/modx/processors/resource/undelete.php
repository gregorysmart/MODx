<?php
/**
 * @package modx
 * @subpackage processors.resource
 */

require_once MODX_PROCESSORS_PATH.'index.php';

$id = $_REQUEST['id'];
$user_id = $modx->getLoginUserID();

$document = $modx->getObject('modResource',$_REQUEST['id']);
if ($document == NULL) $error->failure($modx->lexicon('document_not_found'));


// check permissions on the document
if (!$document->checkPolicy(array('save'=>1, 'undelete'=>1)))
    $error->failure($modx->lexicon('permission_denied'));

$deltime = $document->deletedon;

function getChildren($parent) {
	global $modx;
	global $deltime;

	$kids = $modx->getCollection('modResource',array(
		'parent' => $parent,
		'deleted' => 1,
		'deletedon' => $deltime,
	));

	if(count($kids) > 0) {
		// the document has children documents, we'll need to undelete those too
		foreach ($kids as $kid) {
			//$children[] = $doc;
			$kid->set('deleted',0);
			$kid->set('deletedby',0);
			$kid->set('deletedon',0);
			if (!$kid->save())
				$error->failure($modx->lexicon('document_err_undelete_children'));
			getChildren($kid->id);
		}
	}
}

getChildren($document->id);

//'undelete' the document.

$document->set('deleted',0);
$document->set('deletedby',0);
$document->set('deletedon',0);

if (!$document->save()) $error->failure($modx->lexicon('document_err_undelete_document'));

// log manager action
$modx->logManagerAction('undelete_resource','modDocument',$document->id);

// empty cache
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

$error->success('',$document->get(array('id')));