<?php
/**
 * @package modx
 * @subpackage processors.element.chunk
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('chunk','category');

if (!isset($_POST['id'])) $modx->error->failure($modx->lexicon('chunk_err_ns'));
$chunk = $modx->getObject('modChunk',$_POST['id']);
if ($chunk == null) {
    $modx->error->failure(sprintf($modx->lexicon('chunk_err_id_not_found'),$_POST['id']));
}

$modx->error->success('',$chunk->toArray());