<?php
/**
 * @package modx
 * @subpackage processors.resource
 */

require_once MODX_PROCESSORS_PATH.'index.php';

// FIXME: How should we handle permissions to empty the trash
//if (!$modx->checkSession('mgr')) $error->failure(['permission_denied']);

// get documents
$documents = $modx->getCollection('modResource',array('deleted' => 1));

foreach ($documents as $document) {
	$document->groups = $document->getMany('modDocumentGroupDocument');
	$document->tvds = $document->getMany('modTemplateVarDocument');

	foreach ($document->groups as $pair)
		if (!$pair->remove()) $error->failure($modx->lexicon('document_err_delete_accessperms'));

	foreach ($document->tvds as $tvd)
		if (!$tvd->remove()) $error->failure($modx->lexicon('document_err_delete_tv'));

	if (!$document->remove())
		$error->failure($modx->lexicon('document_err_delete'));

	// see if document's parent has any children left
	$parent = $modx->getObject('modResource',$document->parent);
	if ($parent->id != null) {
		$num_children = $modx->getCount('modResource',array('parent' => $parent->id));
		if ($num_children <= 0) {
			$parent->set('isfolder',false);
			$parent->save();
		}
	}
}

// empty cache
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

$error->success('');