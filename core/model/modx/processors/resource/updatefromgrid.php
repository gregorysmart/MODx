<?php
/**
 * @package modx
 * @subpackage processors.resource
 */

require_once MODX_PROCESSORS_PATH.'index.php';

if (!$modx->hasPermission('save_document')) $modx->error->failure($modx->lexicon('permission_denied'));

$_DATA = $modx->fromJSON($_POST['data']);

if (!isset($_DATA['id'])) $modx->error->failure($modx->lexicon('resource_err_ns'));
$resource = $modx->getObject('modResource',$_DATA['id']);
if ($resource == null) $modx->error->failure($modx->lexicon('resource_err_nfs',array('id' => $_DATA['id'])));

$resource->fromArray($_DATA);
if ($resource->save() === false) {
    $modx->error->failure($modx->lexicon('resource_err_save'));
}

$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache(array (
        "{$resource->context_key}/resources/",
        "{$resource->context_key}/context.cache.php",
    ),
    array(
        'objects' => array('modResource', 'modContext', 'modTemplateVarResource'),
        'publishing' => true
    )
);

$modx->error->success();
