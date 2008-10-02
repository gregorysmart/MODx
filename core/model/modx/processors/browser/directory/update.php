<?php
/**
 * @package modx
 * @subpackage processors.browser.directory
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('file');

if (!$modx->hasPermission('file_manager')) $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['dir']) || $_POST['dir'] == '')
	$modx->error->failure($modx->lexicon('file_folder_err_ns'));


$d = isset($_POST['prependPath']) && $_POST['prependPath'] != 'null' && $_POST['prependPath'] != null
    ? $_POST['prependPath']
    : $modx->config['base_path'].$modx->config['rb_base_dir'];
$olddir = realpath($d.$_POST['dir']);

if (!is_dir($olddir)) $modx->error->failure($modx->lexicon('file_folder_err_invalid'));
if (!is_readable($olddir) || !is_writable($olddir)) {
	$modx->error->failure($modx->lexicon('file_folder_err_perms'));
}


$newdir = strtr(dirname($olddir).'/'.$_POST['name'],'\\','/');

if (!@rename($olddir,$newdir)) {
    $modx->error->failure($modx->lexicon('file_folder_err_rename'));
}

$modx->error->success();