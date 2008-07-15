<?php
/**
 * Duplicates a plugin
 * 
 * @package modx
 * @subpackage manager.element.plugin
 */
if (!$modx->hasPermission('new_plugin')) $modx->error->failure($modx->lexicon('access_denied'));

$modx->loadProcessor('element/plugin/duplicate.php');

// redirect
header('Location: index.php?a=element/plugin/update&id='.$plugin->id);
exit();
