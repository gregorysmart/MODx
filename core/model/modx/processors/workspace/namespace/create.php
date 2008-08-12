<?php
/**
 * @package modx
 * @subpackage processors.workspace.namespace
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('workspace','lexicon');

if (!isset($_POST['name']) || $_POST['name'] == '') {
	$modx->error->failure($modx->lexicon('namespace_err_ns_name'));
}

$namespace = $modx->newObject('modNamespace');
$namespace->set('name',$_POST['name']);
$namespace->set('path',$_POST['path']);

if ($namespace->save() === false) {
	$modx->error->failure($modx->lexicon('namespace_err_create'));
}

$modx->error->success();