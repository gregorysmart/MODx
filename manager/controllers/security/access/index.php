<?php
/**
 * Loads groups/roles management
 *
 * @package modx
 * @subpackage manager.security.access
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('access_denied'));

return $modx->smarty->fetch('security/access/index.tpl');