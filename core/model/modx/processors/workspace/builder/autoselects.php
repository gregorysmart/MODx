<?php
/**
 * @package modx
 * @subpackage processors.workspace.builder
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('workspace','package_builder');

if (!isset($_POST['classes'])) {
	$_POST['classes'] = array();
}
$_SESSION['modx.pb']['autoselects'] = $_POST['classes'];

$modx->error->success();