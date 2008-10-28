<?php
/**
 * @package modx
 * @subpackage processors.context
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('context');

if (!isset($_REQUEST['key'])) {
    return $modx->error->failure($modx->lexicon('context_err_ns'));
}
$context = $modx->getObject('modContext',$_REQUEST['key']);
if ($context == null) {
    return $modx->error->failure(sprintf($modx->lexicon('context_err_nfs'),$_REQUEST['key']));
}
if(!$context->checkPolicy('view')) return $modx->error->failure($modx->lexicon('permission_denied'));

return $modx->error->success('',$context);