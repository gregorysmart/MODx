<?php
/**
 * @package modx
 * @subpackage processors.workspace.packages
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('workspace');

if (!isset($_REQUEST['signature'])) {
    $modx->error->failure($modx->lexicon('package_err_ns'));
}
$modx->log(MODX_LOG_LEVEL_INFO,'Finding package with signature: '.$_REQUEST['signature']);

$package = $modx->getObject('transport.modTransportPackage', $_REQUEST['signature']);
if ($package == null) {
    $modx->error->failure(sprintf($modx->lexicon('package_err_nfs'),$_REQUEST['signature']));
}

$modx->log(MODX_LOG_LEVEL_INFO,'Package found. Preparing to uninstall.');

if (!$package->uninstall()) {
    $modx->error->failure(sprintf($modx->lexicon('package_err_uninstall'),$package->getPrimaryKey()));
}

$modx->log(MODX_LOG_LEVEL_WARN,'Package successfully uninstalled.');
$modx->error->success();