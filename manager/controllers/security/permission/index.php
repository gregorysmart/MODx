<?php
/**
 * Loads the access permissions page
 *
 * @package modx
 * @subpackage manager.security.permission
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('access_denied'));

return $modx->smarty->fetch('security/permissions/index.tpl');