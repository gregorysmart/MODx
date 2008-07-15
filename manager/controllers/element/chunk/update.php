<?php
/**
 * Load update chunk page 
 * 
 * @package modx
 * @subpackage manager.element.chunk
 */
if (!$modx->hasPermission('edit_chunk')) $modx->error->failure($modx->lexicon('access_denied'));

// check to see the snippet editor isn't locked
if ($msg= $modx->checkForLocks($modx->getLoginUserID(),78,'chunk')) $modx->error->failure($msg);

// grab chunk
$chunk = $modx->getObject('modChunk',$_REQUEST['id']);
if ($chunk == null) {
        $modx->error->failure(sprintf($modx->lexicon('chunk_err_id_not_found'),$_REQUEST['id']));
}
// grab category for chunk, assign to parser
$chunk->category = $chunk->getOne('modCategory');
$modx->smarty->assign('chunk',$chunk);

// if chunk is locked, display error 
/* TODO: refactor locked principle to 097 standards
if ($chunk->locked == 1 && $_SESSION['mgrRole'] != 1) {
	$modx->error->failure($modx->lexicon('chunk_err_locked'));
}
*/

// assign RTE if being overridden
$which_editor = isset($_POST['which_editor']) ? $_POST['which_editor'] : 'none';
$modx->smarty->assign('which_editor',$which_editor);


// invoke OnChunkFormPrerender event
$onChunkFormPrerender = $modx->invokeEvent('OnChunkFormPrerender',array('id' => $_REQUEST['id']));
if (is_array($onChunkFormPrerender))
	$onChunkFormPrerender = implode('',$onChunkFormPrerender);
$modx->smarty->assign('onChunkFormPrerender',$onChunkFormPrerender);


// invoke OnRichTextEditorRegister event
$text_editors = $modx->invokeEvent('OnRichTextEditorRegister');
$modx->smarty->assign('text_editors',$text_editors);


// invoke OnChunkFormRender event
$onChunkFormRender = $modx->invokeEvent('OnChunkFormRender',array('id' => $_REQUEST['id']));
if (is_array($onChunkFormRender))
	$onChunkFormRender = implode('', $onChunkFormRender);
$modx->smarty->assign('onChunkFormRender',$onChunkFormRender);


// invoke OnRichTextEditorInit event
if ($modx->config['use_editor'] == 1) {
	$onRTEInit = $modx->invokeEvent('OnRichTextEditorInit',array(
		'editor' => $which_editor,
		'elements' => array('post'),
	));
	if (is_array($onRTEInit))
		$onRTEInit = implode('', $onRTEInit);
	$modx->smarty->assign('onRTEInit',$onRTEInit);
}

// display template
$modx->smarty->display('element/chunk/update.tpl');