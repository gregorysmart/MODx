<?php
/**
 * Deletes a plugin
 * 
 * @package modx
 * @subpackage manager.element.plugin
 */
if (!$modx->hasPermission('delete_plugin')) $modx->error->failure($modx->lexicon('access_denied'));	

$modx->loadProcessor('element/plugin/delete.php');

// redirect
header('Location: index.php?a=welcome');
exit();