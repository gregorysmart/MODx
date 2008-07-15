<?php
/**
 * Deletes a module 
 * 
 * @package modx
 * @subpackage manager.element.module
 */
if (!$modx->hasPermission('delete_module')) $modx->error->failure($modx->lexicon('access_denied'));

$modx->loadProcessor('element/module/delete.php');

// redirect
header('Location: index.php?a=element/module/list');
exit();