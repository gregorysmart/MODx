<?php
/**
 * Deletes a category 
 * 
 * @package modx
 * @subpackage manager.element.category
 */
if (!$modx->hasPermission('save_plugin') ||
	!$modx->hasPermission('save_snippet') ||
	!$modx->hasPermission('save_template') ||
	!$modx->hasPermission('save_module')) $modx->error->failure($modx->lexicon('access_denied'));

$modx->loadProcessor('element/category/delete.php');

header('Location: index.php?a=welcome');
exit();