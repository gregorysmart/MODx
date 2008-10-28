<?php
/**
 * @package modx
 * @subpackage processors.element.chunk
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('chunk');

if (!$modx->hasPermission('delete_chunk')) return $modx->error->failure($modx->lexicon('permission_denied'));

$chunk = $modx->getObject('modChunk',$_REQUEST['id']);
if ($chunk == null) return $modx->error->failure($modx->lexicon('chunk_err_not_found'));

/* invoke OnBeforeChunkFormDelete event */
$modx->invokeEvent('OnBeforeChunkFormDelete',array('id' => $chunk->get('id')));

/* remove chunk */
if ($chunk->remove() == false) {
    return $modx->error->failure($modx->lexicon('chunk_err_remove'));
}

/* invoke OnChunkFormDelete event */
$modx->invokeEvent('OnChunkFormDelete',array('id' => $chunk->get('id')));

/* log manager action */
$modx->logManagerAction('chunk_delete','modChunk',$chunk->get('id'));

/* empty cache */
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

return $modx->error->success();