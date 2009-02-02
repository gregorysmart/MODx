<?php
/**
 * Loads the usergroup create page
 *
 * @package modx
 * @subpackage manager.security.usergroup
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('access_denied'));

return $modx->smarty->fetch('security/usergroup/create.tpl');