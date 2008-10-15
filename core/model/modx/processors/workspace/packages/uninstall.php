<?php
/**
 * @package modx
 * @subpackage processors.workspace.packages
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('workspace');

if (!$modx->hasPermission('packages')) $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_REQUEST['signature'])) {
    $modx->error->failure($modx->lexicon('package_err_ns'));
}
$modx->log(MODX_LOG_LEVEL_INFO,$modx->lexicon('package_uninstall_info_find',array('signature' => $_REQUEST['signature'])));

$package = $modx->getObject('transport.modTransportPackage', $_REQUEST['signature']);
if ($package == null) {
    $modx->error->failure(sprintf($modx->lexicon('package_err_nfs'),$_REQUEST['signature']));
}

$modx->log(MODX_LOG_LEVEL_INFO,$modx->lexicon('package_uninstall_info_prep'));

if ($package->uninstall() == false) {
    $modx->error->failure(sprintf($modx->lexicon('package_err_uninstall'),$package->getPrimaryKey()));
}

$modx->log(MODX_LOG_LEVEL_WARN,$modx->lexicon('package_uninstall_info_success',array('signature' => $package->get('signature'))));
$modx->error->success();