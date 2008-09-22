<?php
/**
 * Loads the help page
 * 
 * @package modx
 * @subpackage manager
 */
if (!$modx->hasPermission('help')) $error->failure($modx->lexicon('permission_denied'));
$modx->smarty->display('help.tpl');