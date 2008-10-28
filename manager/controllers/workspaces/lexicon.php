<?php
/**
 * Loads lexicon management
 *
 * @package modx
 * @subpackage manager.workspaces
 */
if (!$modx->hasPermission('lexicons')) return $modx->error->failure($modx->lexicon('access_denied'));

$modx->smarty->display('workspaces/lexicon/index.tpl');