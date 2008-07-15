<?php
/**
 * @package modx
 * @subpackage processors.system.filesys.file
 */

require_once MODX_PROCESSORS_PATH.'index.php';

if (!$modx->hasPermission('file_manager')) $error->failure($modx->lexicon('permission_denied'));

if (!file_exists($_POST['path']))
	$error->failure($modx->lexicon('file_err_nf'));


// open file
if (!$handle = fopen($_POST['path'],'w'))
	 $error->failure($modx->lexicon('file_err_open').$_POST['path']);


// write to opened file
if (fwrite($handle,$_POST['content']) === false)
	$error->failure($modx->lexicon('file_err_save'));

fclose($handle);

$error->success();