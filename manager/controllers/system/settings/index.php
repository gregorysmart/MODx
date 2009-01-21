<?php
/**
 * Loads the system settings page
 *
 * @package modx
 * @subpackage manager.system.settings
 */
if (!$modx->hasPermission('settings')) return $modx->error->failure($modx->lexicon('access_denied'));

return $modx->smarty->fetch('system/settings/index.tpl');