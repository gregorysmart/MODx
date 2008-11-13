<?php
/**
 * @package modx
 * @subpackage processors.browser.directory
 */
$modx->lexicon->load('file');

if (!$modx->hasPermission('file_manager')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['dir']) || $_POST['dir'] == '')
	return $modx->error->failure($modx->lexicon('file_folder_err_ns'));


$d = isset($_POST['prependPath']) && $_POST['prependPath'] != 'null' && $_POST['prependPath'] != null
    ? $_POST['prependPath']
    : $modx->config['base_path'].$modx->config['rb_base_dir'];
$olddir = realpath($d.$_POST['dir']);

if (!is_dir($olddir)) return $modx->error->failure($modx->lexicon('file_folder_err_invalid'));
if (!is_readable($olddir) || !is_writable($olddir)) {
	return $modx->error->failure($modx->lexicon('file_folder_err_perms'));
}


$newdir = strtr(dirname($olddir).'/'.$_POST['name'],'\\','/');

if (!@rename($olddir,$newdir)) {
    return $modx->error->failure($modx->lexicon('file_folder_err_rename'));
}

return $modx->error->success();