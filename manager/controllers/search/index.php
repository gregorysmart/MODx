<?php
/**
 * Loads the search page
 * 
 * @package modx
 * @subpackage manager.search
 */
if (!$modx->hasPermission('search')) $modx->error->failure($modx->lexicon('access_denied'));

$modx->smarty->display('search/search.tpl');