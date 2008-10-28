<?php
/**
 * Load create plugin page
 *
 * @package modx
 * @subpackage manager.element.plugin
 */
if (!$modx->hasPermission('new_plugin')) return $modx->error->failure($modx->lexicon('access_denied'));

/* grab category if preset */
if (isset($_REQUEST['category'])) {
	$category = $modx->getObject('modCategory',$_REQUEST['category']);
	if ($category != null) $modx->smarty->assign('category',$category);
}

$modx->smarty->display('element/plugin/create.tpl');