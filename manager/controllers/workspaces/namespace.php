<?php
/**
 * Loads lexicon management
 *
 * @package modx
 * @subpackage manager.workspaces
 */
if (!$modx->hasPermission('namespaces')) return $modx->error->failure($modx->lexicon('access_denied'));

$modx->smarty->display('workspaces/namespace/index.tpl');