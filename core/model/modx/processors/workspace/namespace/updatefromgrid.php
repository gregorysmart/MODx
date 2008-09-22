<?php
/**
 * @package modx
 * @subpackage processors.workspace.namespace
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('workspace','lexicon');

if (!$modx->hasPermission('namespaces')) $modx->error->failure($modx->lexicon('permission_denied'));

$_DATA = $modx->fromJSON($_POST['data']);

if (!isset($_DATA['name']) || $_DATA['name'] == '') {
    $modx->error->failure($modx->lexicon('namespace_err_ns'));
}
$namespace = $modx->getObject('modNamespace',$_DATA['name']);
if ($namespace == null) $modx->error->failure($modx->lexicon('namespace_err_nf'));

$namespace->set('path',$_DATA['path']);

if ($namespace->save() === false) {
    $modx->error->failure($modx->lexicon('namespace_err_save'));
}

$modx->error->success();