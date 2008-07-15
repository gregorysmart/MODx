<?php
/**
 * Duplicates a module
 * 
 * @package modx
 * @subpackage manager.element.module
 */
if (!$modx->hasPermission('new_module')) $modx->error->failure($modx->lexicon('access_denied'));	

$modx->loadProcessor('element/module/duplicate.php');

// redirect 
header('Location: index.php?a=element/module/update&id='.$module->id);
exit();