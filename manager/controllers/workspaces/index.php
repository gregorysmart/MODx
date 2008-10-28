<?php
/**
 * Loads the workspace manager
 * 
 * @package modx
 * @subpackage manager.workspaces
 */
if (!$modx->hasPermission('workspaces')) return $modx->error->failure($modx->lexicon('access_denied'));

$modx->smarty->display('workspaces/index.tpl');