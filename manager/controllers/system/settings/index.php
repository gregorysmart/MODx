<?php
/**
 * Loads the system settings page
 *
 * @package modx
 * @subpackage manager.system.settings
 */
if (!$modx->hasPermission('settings')) $modx->error->failure($modx->lexicon('access_denied'));

$modx->smarty->display('system/settings/index.tpl');