<?php
/**
 * Loads the user list
 *
 * @package modx
 * @subpackage manager.security.user
 */
if(!$modx->hasPermission('edit_user')) return $modx->error->failure($modx->lexicon('access_denied'));

return $modx->smarty->fetch('security/user/list.tpl');