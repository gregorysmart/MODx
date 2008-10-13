<?php
/**
 * Refreshes the site cache
 *
 * @package modx
 * @subpackage manager.system
 */
if (!$modx->hasPermission('empty_cache')) $modx->error->failure($modx->lexicon('permission_denied'));

/* clear cache */
$cacheManager= $modx->getCacheManager();

/* invoke OnBeforeCacheUpdate event */
$modx->invokeEvent("OnBeforeCacheUpdate");

$results= $cacheManager->clearCache(array(), array('objects' => '*', 'publishing' => 1));

/* invoke OnSiteRefresh event */
$modx->invokeEvent('OnSiteRefresh');

$num_rows_pub = isset($results['publishing']['published']) ? $results['publishing']['published'] : 0;
$num_rows_unpub = isset($results['publishing']['unpublished']) ? $results['publishing']['unpublished'] : 0;
$modx->smarty->assign('published',sprintf($modx->lexicon('refresh_published'), $num_rows_pub));
$modx->smarty->assign('unpublished',sprintf($modx->lexicon('refresh_unpublished'), $num_rows_unpub));

$modx->smarty->assign('results', $results);

$modx->smarty->display('system/refresh_site.tpl');