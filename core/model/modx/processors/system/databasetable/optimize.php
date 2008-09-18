<?php
/**
 * @package modx
 * @subpackage processors.system.databasetable
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('system_info');

if (!($modx->hasPermission('settings') && ($modx->hasPermission('logs')||$modx->hasPermission('bk_manager')))) {
    $modx->error->failure($modx->lexicon('permission_denied'));
}

if ($_POST['t'] == '' || !isset($_POST['t'])) {
    $modx->error->failure($modx->lexicon('optimize_table_err'));
}

$sql = 'OPTIMIZE TABLE `'.$modx->config['dbname'].'`.'.$_POST['t'];
if ($modx->exec($sql) === false) {
    $modx->error->failure($modx->lexicon('optimize_table_err'));
}

// log manager action
$modx->logManagerAction('database_optimize','',0);

$modx->error->success();