<?php
/**
 * Loads the user list 
 * 
 * @package modx
 * @subpackage manager.security.user
 */
if(!$modx->hasPermission('edit_user')) $modx->error->failure($modx->lexicon('access_denied'));

$modx->smarty->display('security/user/list.tpl');