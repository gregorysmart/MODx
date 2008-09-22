<?php
/**
 * Loads a list of contexts.
 * 
 * @package modx
 * @subpackage manager.context
 */
if(!$modx->hasPermission('view_context')) $modx->error->failure($modx->lexicon('access_denied'));

$modx->smarty->display('context/list.tpl');