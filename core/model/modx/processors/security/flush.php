<?php
/**
 * @package modx
 * @subpackage processors.security
 */

require_once MODX_PROCESSORS_PATH.'index.php';

if (!$modx->hasPermission('flush_sessions')) $modx->error->failure($modx->lexicon('permission_denied')); 

if ($modx->config['session_handler_class'] == 'modSessionHandler') {
    $sessionTable = $modx->getTableName('modSession');
    if (!$modx->query("TRUNCATE {$sessionTable}")) $modx->error->failure($modx->lexicon('flush_sessions_err'));
    $modx->user->endSession();
} else {
    $modx->error->failure($modx->lexicon('flush_sessions_not_supported'));
}
$modx->error->success();