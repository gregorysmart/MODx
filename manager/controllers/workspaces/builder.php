<?php
/**
 * Loads the workspace package builder
 * 
 * @package modx
 * @subpackage manager.workspaces
 */
if (!$modx->hasPermission('package_builder')) $modx->error->failure($modx->lexicon('access_denied'));

$modx->smarty->display('workspaces/builder/index.tpl');