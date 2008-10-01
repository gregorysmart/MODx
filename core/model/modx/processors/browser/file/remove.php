<?php
/**
 * @package modx
 * @subpackage processors.browser.file
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('file');

if (!$modx->hasPermission('file_manager')) $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['file']) || $_POST['file'] == '')
	$modx->error->failure($modx->lexicon('file_err_ns'));

$d = isset($_POST['prependPath']) && $_POST['prependPath'] != null
    ? $_POST['prependPath']
    : $modx->config['base_path'].$modx->config['rb_base_dir'];
$file = $d.$_POST['file'];

// in case rootVisible is true
$file = str_replace('root/','',$file);
$file = str_replace('undefined/','',$file);

if (!file_exists($file))
	$modx->error->failure($modx->lexicon('file_err_nf').': '.$file);
if (!is_readable($file) || !is_writable($file))
	$modx->error->failure($modx->lexicon('file_err_perms_remove'));
if (!is_file($file))
	$modx->error->failure($modx->lexicon('file_err_invalid'));

if (!@unlink($file)) $modx->error->failure($modx->lexicon('file_err_remove'));

$modx->error->success();