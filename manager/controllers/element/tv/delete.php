<?php
/**
 * Deletes a TV 
 * 
 * @package modx
 * @subpackage manager.element.tv
 */
if (!$modx->hasPermission('delete_template')) $modx->error->failure($modx->lexicon('access_denied'));

$modx->loadProcessor('element/tv/delete.php');

// redirect
header('Location: index.php?a=welcome');
exit();