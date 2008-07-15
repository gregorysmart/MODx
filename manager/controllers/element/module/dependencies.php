<?php
/**
 * Loads dependencies for a module page 
 * 
 * @deprecated
 * @package modx
 * @subpackage manager.element.module
 */
if (!$modx->hasPermission('edit_module')) $modx->error->failure($modx->lexicon('access_denied'));

$modx->smarty->display('element/module/dependencies.tpl');