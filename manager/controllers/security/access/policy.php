<?php
/**
 * Loads the policy management page 
 * 
 * @package modx
 * @subpackage manager.security.access
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('access_denied'));

$modx->smarty->display('security/access/policy.tpl');