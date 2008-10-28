<?php
/**
 * Loads the Import by HTML page 
 * 
 * @package modx
 * @subpackage manager.system.import
 */
if (!$modx->hasPermission('import_static')) return $modx->error->failure($modx->lexicon('access_denied'));

$modx->smarty->display('system/import/html.tpl');