<?php
/**
 * Loads the search page
 *
 * @package modx
 * @subpackage manager.search
 */
if (!$modx->hasPermission('search')) return $modx->error->failure($modx->lexicon('access_denied'));

return $modx->smarty->fetch('search/search.tpl');