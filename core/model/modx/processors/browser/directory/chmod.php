<?php
/**
 * @package modx
 * @subpackage processors.browser.directory
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('file');

if (!$modx->hasPermission('file_manager')) $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['mode']) || $_POST['mode'] == '')
	$modx->error->failure($_lang['file_err_chmod_ns']);
if (!isset($_POST['dir']) || $_POST['dir'] == '')
	$modx->error->failure($modx->lexicon('file_folder_err_ns'));

$d = isset($_POST['prependPath']) && $_POST['prependPath'] != null
    ? $_POST['prependPath']
    : $modx->config['base_path'].$modx->config['rb_base_dir'];
$directory = realpath($d.$_POST['dir']);

if (!is_dir($directory)) $modx->error->failure($modx->lexicon('file_folder_err_invalid'));
if (!is_readable($directory) || !is_writable($directory)) {
	$modx->error->failure($modx->lexicon('file_folder_err_perms'));
}
if (!@chmod($directory,$_POST['mode'])) {
	$modx->error->failure($modx->lexicon('file_err_chmod'));
}

$modx->error->success();