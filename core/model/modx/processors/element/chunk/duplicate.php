<?php
/**
 * @package modx
 * @subpackage processors.element.chunk
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('chunk');

if (!$modx->hasPermission('new_chunk')) $modx->error->failure($modx->lexicon('permission_denied'));

/* Get old chunk */
$old_chunk = $modx->getObject('modChunk',$_REQUEST['id']);
if ($old_chunk == null) $modx->error->failure($modx->lexicon('chunk_err_not_found'));

$newname = isset($_POST['name'])
    ? $_POST['name']
    : $modx->lexicon('duplicate_of').$old_chunk->get('name');

/* duplicate chunk */
$chunk = $modx->newObject('modChunk');
$chunk->set('name',$newname);
$chunk->set('description',$old_chunk->get('description'));
$chunk->set('snippet',$old_chunk->get('snippet'));
$chunk->set('category',$old_chunk->get('category'));
$chunk->set('locked',$old_chunk->get('locked'));

if ($chunk->save() === false) {
    $modx->error->failure($modx->lexicon('chunk_err_duplicate'));
}

/* empty cache */
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

/* log manager action */
$modx->logManagerAction('chunk_duplicate','modChunk',$chunk->get('id'));

$modx->error->success('',$chunk->get(array('id', 'name', 'description', 'category', 'locked')));