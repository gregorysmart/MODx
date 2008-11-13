<?php
/**
 * @package modx
 * @subpackage processors.workspace.namespace
 */
$modx->lexicon->load('workspace','lexicon');

if (!$modx->hasPermission('namespaces')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['name']) || $_POST['name'] == '') {
	return $modx->error->failure($modx->lexicon('namespace_err_ns_name'));
}

$namespace = $modx->newObject('modNamespace');
$namespace->set('name',$_POST['name']);
$namespace->set('path',$_POST['path']);

if ($namespace->save() === false) {
	return $modx->error->failure($modx->lexicon('namespace_err_create'));
}

return $modx->error->success();