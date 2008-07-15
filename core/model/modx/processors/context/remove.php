<?php
/**
 * @package modx
 * @subpackage processors.context
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('context');

if (!$modx->hasPermission('delete_context')) $error->failure($modx->lexicon('permission_denied'));

$context= $modx->getObject('modContext', $_REQUEST['key']);
if ($context == null) $error->failure($modx->lexicon('context_err_nf'));
if ($context->key == 'web' || $context->key == 'mgr' || $context->key == 'connector') $error->failure($modx->lexicon('permission_denied'));

if (!$context->remove()) {
    $error->failure($modx->lexicon('context_err_remove'));
}

// log manager action
$modx->logManagerAction('context_delete','modContext',$context->id);

// clear cache
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

$error->success();