<?php
/**
 * Loads groups/roles management
 * 
 * @package modx
 * @subpackage manager.security.access
 */
if (!$modx->hasPermission('access_permissions')) $modx->error->failure($modx->lexicon('access_denied'));

$modx->smarty->display('security/access/index.tpl');