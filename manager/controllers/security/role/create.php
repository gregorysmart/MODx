<?php
/**
 * Loads the create role page
 * 
 * @package modx
 * @subpackage manager.security.role
 */
if(!$modx->hasPermission('new_role')) return $modx->error->failure($modx->lexicon('access_denied'));

$modx->smarty->display('security/role/create.tpl');