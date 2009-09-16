<?php
/**
 * Loads the view context preview page.
 *
 * @package modx
 * @subpackage manager.context
 */
/* get context by key */
$context= $modx->getObjectGraph('modContext', '{"ContextSettings":{}}', $_REQUEST['key']);
if ($context == null) {
    return $modx->error->failure(sprintf($modx->lexicon('context_with_key_not_found'), $_REQUEST['key']));
}
if (!$context->checkPolicy('view')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* prepare context data for display */
if (!$context->prepare()) {
    return $modx->error->failure($modx->lexicon('context_err_load_data'), $context->toArray());
}

/* assign context to smarty and display */
$modx->smarty->assign('context', $context);
return $modx->smarty->fetch('context/view.tpl');