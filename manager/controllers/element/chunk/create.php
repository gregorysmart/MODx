<?php
/**
 * Load create chunk page
 *
 * @package modx
 * @subpackage manager.element.module
 */
if (!$modx->hasPermission('new_chunk')) $modx->error->failure($modx->lexicon('access_denied'));

/* grab default category if specified */
if (isset($_REQUEST['category'])) {
	$category = $modx->getObject('modCategory',$_REQUEST['category']);
	if ($category != null) $modx->smarty->assign('category',$category);
}

/* if RTE is being reset, switch */
$which_editor = isset($_POST['which_editor']) ? $_POST['which_editor'] : 'none';
$modx->smarty->assign('which_editor',$which_editor);

/* invoke OnChunkFormPrerender event */
$onChunkFormPrerender = $modx->invokeEvent('OnChunkFormPrerender',array('id' => 0));
if (is_array($onChunkFormPrerender)) {
	$onChunkFormPrerender = implode('',$onChunkFormPrerender);
}
$modx->smarty->assign('onChunkFormPrerender',$onChunkFormPrerender);

/* invoke OnChunkFormRender event */
$onChunkFormRender = $modx->invokeEvent('OnChunkFormRender',array('id' => 0));
if (is_array($onChunkFormRender)) {
	$onChunkFormRender = implode('', $onChunkFormRender);
}
$modx->smarty->assign('onChunkFormRender',$onChunkFormRender);


/* invoke OnRichTextEditorInit event */
if ($modx->config['use_editor'] == 1) {
	$onRTEInit = $modx->invokeEvent('OnRichTextEditorInit',array(
		'editor' => $which_editor,
		'elements' => array('post'),
	));
	if (is_array($onRTEInit)) {
		$onRTEInit = implode('', $onRTEInit);
    }
	$modx->smarty->assign('onRTEInit',$onRTEInit);
}

/* display template */
$modx->smarty->display('element/chunk/create.tpl');