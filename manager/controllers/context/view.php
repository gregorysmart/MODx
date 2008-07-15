<?php
/**
 * Loads the view context preview page.
 * 
 * @package modx
 * @subpackage manager.context
 */
// get context by key
$context= $modx->getObjectGraph('modContext', '{"modContextSetting":{}}', $_REQUEST['key']);
if ($context == null) {
    $modx->error->failure(sprintf($modx->lexicon('context_with_key_not_found'), $_REQUEST['key']));
}

// prepare context data for display
if (!$context->prepare()) {
    $modx->error->failure($modx->lexicon('context_err_load_data'), $context->toArray());
}

// assign context to smarty and display
$modx->smarty->assign('context', $context);
$modx->smarty->display('context/view.tpl');