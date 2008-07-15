<?php
/**
 * Loads the Import Resources page 
 * 
 * @package modx
 * @subpackage manager.system.import
 */
if (!$modx->hasPermission('import_static')) $modx->error->failure($modx->lexicon('access_denied'));

$modx->smarty->display('system/import/index.tpl');