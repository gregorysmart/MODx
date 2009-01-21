<?php
/**
 * Loads lexicon management
 *
 * @package modx
 * @subpackage manager.workspaces
 */
if (!$modx->hasPermission('namespaces')) return $modx->error->failure($modx->lexicon('access_denied'));

return $modx->smarty->fetch('workspaces/namespace/index.tpl');