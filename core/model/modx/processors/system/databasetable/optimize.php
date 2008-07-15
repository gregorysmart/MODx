<?php
/**
 * @package modx
 * @subpackage processors.system.databasetable
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('system_info');

if (!($modx->hasPermission('settings') && ($modx->hasPermission('logs')||$modx->hasPermission('bk_manager')))) $error->failure($modx->lexicon('permission_denied'));

if ($_POST['t'] == '' || !isset($_POST['t'])) $error->failure($modx->lexicon('optimize_table_err'));

if(!$modx->exec('OPTIMIZE TABLE `'.$modx->config['dbname'].'`.'.$_POST['t'])) $error->failure($modx->lexicon('optimize_table_err'));

// log manager action
$modx->logManagerAction('database_optimize','',0);

$error->success();