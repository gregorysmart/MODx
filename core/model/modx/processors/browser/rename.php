<?php
/**
 * @package modx
 * @subpackage processors.browser
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('file');

if (!$modx->hasPermission('file_manager')) $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['file']) || $_POST['file'] == '')
    $modx->error->failure($modx->lexicon('file_err_ns'));

$d = isset($_POST['prependPath']) && $_POST['prependPath'] != null
    ? $_POST['prependPath']
    : $modx->config['base_path'].$modx->config['rb_base_dir'];
$old_file = realpath($d.$_POST['file']);

if (!is_readable($old_file) || !is_writable($old_file))
    $modx->error->failure($modx->lexicon('file_err_perms_rename'));


$new_file = strtr(dirname($old_file).'/'.$_POST['new_name'],'\\','/');

if (!@rename($old_file,$new_file)) {
    $modx->error->failure($modx->lexicon('file_err_rename'));
}

$modx->error->success();