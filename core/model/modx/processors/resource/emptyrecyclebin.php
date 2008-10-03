<?php
/**
 * @package modx
 * @subpackage processors.resource
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('resource');

if (!$modx->hasPermission('purge_deleted')) $modx->error->failure($modx->lexicon('permission_denied'));

// get documents
$resources = $modx->getCollection('modResource',array('deleted' => 1));

foreach ($resources as $resource) {
	$resource->groups = $resource->getMany('modResourceGroupResource');
	$resource->tvds = $resource->getMany('modTemplateVarResource');

	foreach ($resource->groups as $pair) {
	   $pair->remove();
    }

	foreach ($resource->tvds as $tvd) {
		$tvd->remove();
    }

	if ($resource->remove() == false) {
		$modx->error->failure($modx->lexicon('resource_err_delete'));
    }

	// see if document's parent has any children left
	$parent = $modx->getObject('modResource',$resource->parent);
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

$modx->error->success();