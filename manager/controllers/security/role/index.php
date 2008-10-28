<?php
/**
 * Loads role management
 * 
 * @package modx
 * @subpackage manager.security.role
 */
if(!$modx->hasPermission('edit_role')) return $modx->error->failure($modx->lexicon('access_denied'));

$modx->smarty->display('security/role/list.tpl');