<?php
/**
 * Loads the system settings page 
 * 
 * @package modx
 * @subpackage manager.system.settings
 */
if (!$modx->hasPermission('settings')) $modx->error->failure($modx->lexicon('access_denied'));

// check to see the edit settings page isn't locked
if ($msg= $modx->checkForLocks($modx->getLoginUserID(),17,'lock_settings_msg')) {
    $modx->error->failure($msg);
}

$modx->smarty->display('system/settings/index.tpl');