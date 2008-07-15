<?php
/**
 * Duplicates a chunk
 * 
 * @package modx
 * @subpackage manager.element.chunk
 */
$modx->loadProcessor('element/chunk/duplicate.php');

header('Location: index.php?a=element/chunk/update&id='.$new_chunk->id);
exit();