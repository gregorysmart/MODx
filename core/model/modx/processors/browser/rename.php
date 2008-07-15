<?php
/**
 * @package modx
 * @subpackage processors.browser
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('file');

if (!isset($_POST['file']) || $_POST['file'] == '')
    $error->failure($modx->lexicon('file_err_ns'));

$old_file = realpath($modx->config['base_path'].$modx->config['rb_base_dir'].$_POST['file']);

if (!is_readable($old_file) || !is_writable($old_file))
    $error->failure($modx->lexicon('file_err_perms_rename'));


$new_file = strtr(dirname($old_file).'/'.$_POST['new_name'],'\\','/');

if (!@rename($old_file,$new_file)) $error->failure($modx->lexicon('file_err_rename'));

$error->success();