<?php
/**
 * Loads the resource group page
 *
 * @package modx
 * @subpackage manager.security.permission
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('access_denied'));

return $modx->smarty->fetch('security/resourcegroup/index.tpl');