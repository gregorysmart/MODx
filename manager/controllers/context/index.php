<?php
/**
 * Loads a list of contexts.
 *
 * @package modx
 * @subpackage manager.context
 */
if(!$modx->hasPermission('view_context')) return $modx->error->failure($modx->lexicon('access_denied'));

$modx->regClientStartupScript($modx->config['manager_url'].'assets/modext/widgets/system/modx.grid.context.js');
$modx->regClientStartupScript($modx->config['manager_url'].'assets/modext/sections/context/list.js');

return $modx->smarty->fetch('context/list.tpl');