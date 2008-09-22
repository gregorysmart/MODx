<?php
/**
 * Loads the manager logs page 
 * 
 * @package modx
 * @subpackage manager.system.logs
 */
if (!$modx->hasPermission('logs')) $modx->error->failure($modx->lexicon('access_denied'));
$modx->smarty->display('system/logs/index.tpl');