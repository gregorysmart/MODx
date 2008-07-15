<?php
/**
 * Deletes a snippet
 * 
 * @package modx
 * @subpackage manager.resource.snippet
 */
if (!$modx->hasPermission('delete_snippet')) $modx->error->failure($modx->lexicon('access_denied'));	

$modx->loadProcessor('element/snippet/delete.php');

// redirect
header('Location: index.php?a=welcome');
exit();