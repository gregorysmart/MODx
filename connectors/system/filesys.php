<?php
require_once dirname(dirname(__FILE__)).'/index.php';
switch ($_REQUEST['action']) {
	case 'removeFile':
        $modx->request->handleRequest('system/filesys/file','remove');
		break;
	case 'updateFile':
		$modx->request->handleRequest('system/filesys/file','update');
		break;
	case 'unzipFile':
		$modx->request->handleRequest('system/filesys/file','unzip');
		break;
	case 'createFolder':
		$modx->request->handleRequest('system/filesys/folder','create');
		break;
	case 'removeFolder':
		$modx->request->handleRequest('system/filesys/folder','remove');
		break;
}