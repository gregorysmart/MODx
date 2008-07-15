<?php
/**
 * @package modx
 * @subpackage processors.browser.directory
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('file');


if (!isset($_POST['mode']) || $_POST['mode'] == '')
	$error->failure($_lang['file_err_chmod_ns']);
if (!isset($_POST['dir']) || $_POST['dir'] == '')
	$error->failure($modx->lexicon('file_folder_err_ns'));

$directory = realpath($modx->config['base_path'].$modx->config['rb_base_dir'].$_POST['dir']);

if (!is_dir($directory)) $error->failure($modx->lexicon('file_folder_err_invalid'));
if (!is_readable($directory) || !is_writable($directory)) {
	$error->failure($modx->lexicon('file_folder_err_perms'));
}
if (!@chmod($directory,$_POST['mode'])) {
	$error->failure($modx->lexicon('file_err_chmod'));
}

$error->success();