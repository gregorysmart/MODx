<?php
/**
 * @package modx
 * @subpackage processors.system.filesys.folder
 */

require_once MODX_PROCESSORS_PATH.'index.php';

if (!$modx->hasPermission('file_manager')) $error->failure($modx->lexicon('permission_denied'));

// prevent subdir hack
$_POST['name'] = str_replace("..\\",'',str_replace("../",'',$_POST['name']));

// validate name
if (!isset($_POST['name']) || $_POST['name'] == '')
	$error->failure($modx->lexicon('file_folder_err_ns_name'));

if (!is_dir($_POST['path']))
	$error->failure($modx->lexicon('file_folder_err_invalid_path'));

// setup vars
$amode = isset($modx->config['new_folder_permissions']) ? octdec($modx->config['new_folder_permissions']) : 0777;
$new_folder = $_POST['path'].'/'.$_POST['name'];

if (file_exists($new_folder))
	$error->failure($modx->lexicon('file_folder_err_ae'));


// create folder
if (!mkdirs($new_folder,$amode)) {
	$error->failure($modx->lexicon('file_folder_err_create'));
} else {
	if (!@chmod($new_folder,$amode)) {
		$error->failure($modx->lexicon('file_folder_err_chmod'));
	}
}


// recursive mkdir function
function mkdirs($strPath, $mode){
	if (is_dir($strPath)) return true;
	$pStrPath = dirname($strPath);
	if (!mkdirs($pStrPath, $mode)) return false;
	return @mkdir($strPath);
}

$error->success();