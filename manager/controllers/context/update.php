<?php
/**
 * Loads the view context preview page.
 *
 * @package modx
 * @subpackage manager.context
 */
if(!$modx->hasPermission('edit_context')) return $modx->error->failure($modx->lexicon('access_denied'));

/* get context by key */
$context= $modx->getObjectGraph('modContext', '{"modContextSetting":{}}', $_REQUEST['key']);
if ($context == null) {
    return $modx->error->failure(sprintf($modx->lexicon('context_with_key_not_found'), $_REQUEST['key']));
}
if (!$context->checkPolicy(array('view' => true, 'save' => true))) return $modx->error->failure($modx->lexicon('permission_denied'));

/* prepare context data for display */
if (!$context->prepare()) {
    return $modx->error->failure($modx->lexicon('context_err_load_data'), $context->toArray());
}

/*  assign context to smarty and display */
$modx->smarty->assign('context', $context);
$modx->smarty->display('context/update.tpl');