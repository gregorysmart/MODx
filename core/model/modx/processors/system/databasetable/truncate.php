<?php
/**
 * @package modx
 * @subpackage processors.system.databasetable
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('system_info');


if (!($modx->hasPermission('database_truncate'))) {
    $modx->error->failure($modx->lexicon('permission_denied'));
}

if ($_POST['t'] == '' || !isset($_POST['t'])) {
    $modx->error->failure($modx->lexicon('truncate_table_err'));
}

$sql = 'TRUNCATE TABLE `'.$modx->config['dbname'].'`.'.$_POST['t'];
if ($modx->exec($sql) === false) {
    $modx->error->failure($modx->lexicon('truncate_table_err'));
}

// log manager action
$modx->logManagerAction('database_truncate','',0);

$modx->error->success();