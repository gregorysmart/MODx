<?php
/**
 * @package modx
 * @subpackage processors.resource
 */

require_once MODX_PROCESSORS_PATH.'index.php';

$_DATA = $modx->fromJSON($_POST['data']);

if (!isset($_DATA['id'])) $error->failure($modx->lexicon('document_err_not_specified'));
$resource = $modx->getObject('modResource',$_DATA['id']);
if ($resource == null) $error->failure($modx->lexicon('document_not_found'));

$resource->fromArray($_DATA);

if ($resource->save() === false) {
    $modx->error->failure($modx->lexicon('document_err_save'));
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
