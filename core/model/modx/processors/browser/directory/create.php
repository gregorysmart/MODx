<?php
/**
 * @package modx
 * @subpackage processors.browser.directory
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('file');

if (!$modx->hasPermission('file_manager')) $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['name']) || $_POST['name'] == '')
	$modx->error->failure($modx->lexicon('file_folder_err_ns'));

if (!isset($_POST['parent'])) {
	$_POST['parent'] = '';
}
$d = isset($_POST['prependPath']) && $_POST['prependPath'] != 'null' && $_POST['prependPath'] != null
    ? $_POST['prependPath']
    : $modx->config['base_path'].$modx->config['rb_base_dir'];
$parentdir = $d.$_POST['parent'].'/';

if (!is_dir($parentdir)) $modx->error->failure($modx->lexicon('file_folder_err_parent_invalid'));
if (!is_readable($parentdir) || !is_writable($parentdir)) {
	$modx->error->failure($modx->lexicon('file_folder_err_perms_parent'));
}

$newdir = $parentdir.'/'.$_POST['name'];

if (file_exists($newdir)) $modx->error->failure($modx->lexicon('file_folder_err_ae'));

if (!@mkdir($newdir,0755)) {
	$modx->error->failure($modx->lexicon('file_folder_err_create'));
}

$modx->error->success();