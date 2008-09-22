<?php
/**
 * @package modx
 * @subpackage processors.context
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('context');

if (!isset($_REQUEST['key'])) {
    $modx->error->failure($modx->lexicon('context_err_ns'));
}
$context = $modx->getObject('modContext',$_REQUEST['key']);
if ($context == null) {
    $modx->error->failure(sprintf($modx->lexicon('context_err_nfs'),$_REQUEST['key']));
}
if(!$context->checkPolicy('view')) $modx->error->failure($modx->lexicon('permission_denied'));

$modx->error->success('',$context);