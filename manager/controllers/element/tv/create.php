<?php
/**
 * Load create tv page
 *
 * @package modx
 * @subpackage manager.element.tv
 */
if (!$modx->hasPermission('new_template')) return $modx->error->failure($modx->lexicon('access_denied'));

/* preset category if specified */
if (isset($_REQUEST['category'])) {
	$category = $modx->getObject('modCategory',$_REQUEST['category']);
	if ($category != null) $modx->smarty->assign('category',$category);
}

/* get available RichText Editors */
$RTEditors = '';
$evtOut = $modx->invokeEvent('OnRichTextEditorRegister',array('forfrontend' => 1));
if(is_array($evtOut)) $RTEditors = implode(',',$evtOut);
$modx->smarty->assign('RTEditors',$RTEditors);

/* invoke OnTVFormPrerender event */
$onTVFormPrerender = $modx->invokeEvent('OnTVFormPrerender',array('id' => 0));
if(is_array($onTVFormPrerender)) $onTVFormPrerender = implode('',$onTVFormPrerender);
$modx->smarty->assign('onTVFormPrerender',$onTVFormPrerender);

/* load categories */
$categories = $modx->getCollection('modCategory');
$modx->smarty->assign('categories',$categories);

/* invoke OnTVFormRender event */
$onTVFormRender = $modx->invokeEvent('OnTVFormRender',array('id' => 0));
if (is_array($onTVFormRender)) $onTVFormRender = implode('',$onTVFormRender);
$modx->smarty->assign('onTVFormRender',$onTVFormRender);

/* display template */
return $modx->smarty->fetch('element/tv/create.tpl');