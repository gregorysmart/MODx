<?php
/**
 * @package modx
 * @subpackage processors.element.chunk
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('chunk');

if (!$modx->hasPermission('delete_chunk')) $error->failure($modx->lexicon('permission_denied'));

$chunk = $modx->getObject('modChunk',$_REQUEST['id']);
if ($chunk == null) $error->failure($modx->lexicon('chunk_err_not_found'));

// invoke OnBeforeChunkFormDelete event
$modx->invokeEvent('OnBeforeChunkFormDelete',array('id' => $chunk->id));

// remove chunk
if (!$chunk->remove()) $error->failure($modx->lexicon('chunk_err_remove'));

// invoke OnChunkFormDelete event
$modx->invokeEvent('OnChunkFormDelete',array('id' => $chunk->id));

// log manager action
$modx->logManagerAction('chunk_delete','modChunk',$chunk->id);

// empty cache
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

$error->success();