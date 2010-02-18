<?php
/**
 * Empties the recycle bin.
 *
 * @return boolean
 *
 * @package modx
 * @subpackage processors.resource
 */
if (!$modx->hasPermission('purge_deleted')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('resource');

$modx->invokeEvent('OnBeforeEmptyTrash');

/* get resources */
$resources = $modx->getCollection('modResource',array('deleted' => true));
$count = count($resources);

foreach ($resources as $resource) {
    if (!$resource->checkPolicy('delete')) continue;

    $resource->groups = $resource->getMany('ResourceGroupResources');
    $resource->tvds = $resource->getMany('TemplateVarResources');

    foreach ($resource->groups as $pair) {
       $pair->remove();
    }

    foreach ($resource->tvds as $tvd) {
        $tvd->remove();
    }

    if ($resource->remove() == false) {
        return $modx->error->failure($modx->lexicon('resource_err_delete'));
    }
}

$modx->invokeEvent('OnEmptyTrash',array(
    'num_deleted' => $count,
));

/* empty cache */
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

return $modx->error->success();