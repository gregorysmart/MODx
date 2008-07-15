<?php
require_once dirname(dirname(__FILE__)).'/index.php';
if ($_REQUEST['action'] == 'sort') {
	$modx->request->handleRequest('layout/tree/element','sort');
} else {
	$modx->request->handleRequest('element/module/dependency');
}