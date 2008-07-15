<?php
/**
 * Duplicates a snippet
 * 
 * @package modx
 * @subpackage manager.element.snippet
 */
if (!$modx->hasPermission('new_snippet')) $modx->error->failure($modx->lexicon('access_denied'));
	
$modx->loadProcessor('element/snippet/duplicate.php');

// redirect
header('Location: index.php?a=element/snippet/update&id='.$snippet->id);
exit();