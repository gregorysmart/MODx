<?php
/**
 * Grabs a context
 *
 * @param string $key The key of the context
 *
 * @package modx
 * @subpackage processors.context
 */
if (!$modx->hasPermission('view_context')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('context');

if (!isset($scriptProperties['key'])) {
    return $modx->error->failure($modx->lexicon('context_err_ns'));
}
$context = $modx->getObject('modContext',$scriptProperties['key']);
if ($context == null) {
    return $modx->error->failure(sprintf($modx->lexicon('context_err_nfs'),$scriptProperties['key']));
}
if(!$context->checkPolicy('view')) return $modx->error->failure($modx->lexicon('permission_denied'));

return $modx->error->success('',$context);