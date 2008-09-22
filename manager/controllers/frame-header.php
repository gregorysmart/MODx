<?php
/**
 * Loads the internal frame header
 * 
 * @package modx
 * @subpackage manager
 */
if (!$modx->hasPermission('frames')) $error->failure($modx->lexicon('permission_denied'));
$modx->smarty->display('frame-header.tpl');