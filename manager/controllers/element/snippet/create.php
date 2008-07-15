<?php
/**
 * Load create snippet page 
 * 
 * @package modx
 * @subpackage manager.element.snippet
 */
if (!$modx->hasPermission('new_snippet')) $modx->error->failure($modx->lexicon('access_denied'));

// preset category if specified  
if (isset($_REQUEST['category'])) {
	$category = $modx->getObject('modCategory',$_REQUEST['category']);
	if ($category != null) $modx->smarty->assign('category',$category);
}

// invoke OnSnipFormPrerender event
$onSnipFormPrerender = $modx->invokeEvent('OnSnipFormPrerender',array('id' => 0));
if (is_array($onSnipFormPrerender)) $onSnipFormPrerender = implode('',$onSnipFormPrerender);
$modx->smarty->assign('onSnipFormPrerender',$onSnipFormPrerender);

// invoke onSnipFormRender event
$onSnipFormRender = $modx->invokeEvent('OnSnipFormRender',array('id' => 0));
if (is_array($onSnipFormRender)) $onSnipFormRender = implode('',$onSnipFormRender);
$modx->smarty->assign('onSnipFormRender',$onSnipFormRender);

$modx->smarty->display('element/snippet/create.tpl');