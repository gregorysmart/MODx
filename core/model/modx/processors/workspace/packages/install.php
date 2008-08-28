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
$package= $modx->getObject('transport.modTransportPackage',$_REQUEST['signature']);
if ($package == null) {
    $modx->error->failure(sprintf($modx->lexicon('package_err_nfs'),$_REQUEST['signature']));
}

if (!$package->install()) {
    $modx->error->failure(sprintf($modx->lexicon('package_err_install'),$package->get('signature')));
} else {
    $msg = sprintf($modx->lexicon('package_installed'),$package->get('signature'));
    $modx->log(XPDO_LOG_LEVEL_INFO,$msg);
    $modx->error->success($msg);
}
$modx->error->failure($modx->lexicon('package_err_install_gen'));