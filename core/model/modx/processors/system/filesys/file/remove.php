<?php
/**
 * @package modx
 * @subpackage processors.system.filesys.file
 */

require_once MODX_PROCESSORS_PATH.'index.php';

if (!$modx->hasPermission('file_manager')) $error->failure($modx->lexicon('permission_denied'));

$file = $_POST['path'].$_POST['file'];

if (!file_exists($file)) $error->failure($modx->lexicon('file_err_nf'));

if (!@unlink($file)) $error->failure($modx->lexicon('file_err_remove'));

$error->success();