<?php
/**
 * @package modx
 * @subpackage processors.browser.directory
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('file');

if (!isset($_POST['dir']) || $_POST['dir'] == '')
	$error->failure($modx->lexicon('file_folder_err_ns'));

$olddir = realpath($modx->config['base_path'].$modx->config['rb_base_dir'].$_POST['dir']);

if (!is_dir($olddir)) $error->failure($modx->lexicon('file_folder_err_invalid'));
if (!is_readable($olddir) || !is_writable($olddir))
	$error->failure($modx->lexicon('file_folder_err_perms'));


$newdir = strtr(dirname($olddir).'/'.$_POST['name'],'\\','/');

if (!@rename($olddir,$newdir)) $error->failure($modx->lexicon('file_folder_err_rename'));

$error->success();