<?php
/**
 * @package modx
 * @subpackage processors.workspace.namespace
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('workspace','lexicon');

if (!isset($_POST['name']) || $_POST['name'] == '') {
    $modx->error->failure($modx->lexicon('namespace_err_ns'));
}
$namespace = $modx->getObject('modNamespace',$_POST['name']);
if ($namespace == null) $modx->error->failure($modx->lexicon('namespace_err_nf'));

if ($namespace->remove() === false) {
    $modx->error->failure($modx->lexicon('namespace_err_remove'));
}

$modx->error->success();