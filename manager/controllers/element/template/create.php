<?php
/**
 * Load create template page
 *
 * @package modx
 * @subpackage manager.element.template
 */
if (!$modx->hasPermission('new_template')) return $modx->error->failure($modx->lexicon('access_denied'));

/* preset category if specified */
if (isset($_REQUEST['category'])) {
	$category = $modx->getObject('modCategory',$_REQUEST['category']);
	if ($category != null) $modx->smarty->assign('category',$category);
}

/* invoke OnTempFormPrerender event */
$onTempFormPrerender = $modx->invokeEvent('OnTempFormPrerender',array('id' => 0));
if (is_array($onTempFormPrerender)) $onTempFormPrerender = implode('',$onTempFormPrerender);
$modx->smarty->assign('onTempFormPrerender',$onTempFormPrerender);

/* invoke OnTempFormRender event */
$onTempFormRender = $modx->invokeEvent('OnTempFormRender',array('id' => 0));
if (is_array($onTempFormRender)) $onTempFormRender = implode('',$onTempFormRender);
$modx->smarty->assign('onTempFormRender',$onTempFormRender);

/* display template */
return $modx->smarty->fetch('element/template/create.tpl');