<?php
/**
 * Deletes a chunk
 * 
 * @package modx
 * @subpackage manager.element.chunk
 */
if(!$modx->hasPermission('delete_chunk')) $modx->error->failure($modx->lexicon('access_denied'));

$modx->loadProcessor('element/chunk/delete.php');

// refresh
header('Location: index.php?a=welcome');
exit();