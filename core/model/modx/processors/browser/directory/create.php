<?php
/**
 * @package modx
 * @subpackage processors.browser.directory
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('file');


if (!isset($_POST['name']) || $_POST['name'] == '')
	$error->failure($modx->lexicon('file_folder_err_ns'));

if (!isset($_POST['parent'])) {
	$_POST['parent'] = '';
}
$parentdir = $modx->config['base_path'].$modx->config['rb_base_dir'].$_POST['parent'].'/';

if (!is_dir($parentdir)) $error->failure($modx->lexicon('file_folder_err_parent_invalid'));
if (!is_readable($parentdir) || !is_writable($parentdir)) {
	$error->failure($modx->lexicon('file_folder_err_perms_parent'));
}

$newdir = $parentdir.'/'.$_POST['name'];

if (file_exists($newdir)) $error->failure($modx->lexicon('file_folder_err_ae'));

if (!@mkdir($newdir,0755)) {
	$error->failure($modx->lexicon('file_folder_err_create'));
}

$error->success();