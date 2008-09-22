<?php
/**
 * @package modx
 * @subpackage processors.browser.directory
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('file');

if (!$modx->hasPermission('file_manager')) $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['dir']) || $_POST['dir'] == '')
	$error->failure($modx->lexicon('file_folder_err_ns'));

$directory = realpath($modx->config['base_path'].$modx->config['rb_base_dir'].$_POST['dir']);

if (!is_dir($directory)) $error->failure($modx->lexicon('file_folder_err_invalid'));
if (!is_readable($directory) || !is_writable($directory)) {
	$error->failure($modx->lexicon('file_folder_err_perms_upload'));
}

foreach ($_FILES as $file) {
	if ($file['error'] != 0) continue;
	if ($file['name'] == '') continue;

	$newloc = strtr($directory.'/'.$file['name'],'\\','/');

	if (!@move_uploaded_file($file['tmp_name'],$newloc)) {
		$error->failure($modx->lexicon('file_err_upload'));
	}
}

$error->success();