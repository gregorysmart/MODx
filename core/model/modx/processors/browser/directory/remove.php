<?php
/**
 * @package modx
 * @subpackage processors.browser.directory
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('file');

if (!isset($_POST['dir']) || $_POST['dir'] == '')
	$error->failure($modx->lexicon('file_folder_err_ns'));

$directory = $modx->config['base_path'].$modx->config['rb_base_dir'].$_POST['dir'];

if (!is_dir($directory)) $error->failure($modx->lexicon('file_folder_err_invalid'));
if (!is_readable($directory) || !is_writable($directory))
	$error->failure($modx->lexicon('file_folder_err_perms_remove'));

if (!rmdirr($directory)) $error->failure($modx->lexicon('file_folder_err_remove'));

$error->success();

function rmdirr($dr) {
	if (!is_writable($dr)) {
		if (!@chmod($dr,0777)) {
			return false;
		}
	}
	$d = dir($dr);
	if (!is_object($d)) return false;
	while (false !== ($entry = $d->read())) {
		if ($entry == '.' || $entry == '..') continue;
		$entry = $dr.'/'.$entry;
		if (is_dir($entry)) {
			if (!rrmdir($entry)) return false;
			continue;
		}
		if (!@unlink($entry)) {
			$d->close();
			return false;
		}
	}
	$d->close();
	rmdir($dr);
	return true;
}

$error->success();