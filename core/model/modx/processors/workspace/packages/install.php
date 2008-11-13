<?php
/**
 * @package modx
 * @subpackage processors.workspace.packages
 */
$modx->lexicon->load('workspace');

if (!$modx->hasPermission('packages')) return $modx->error->failure($modx->lexicon('permission_denied'));

$modx->log(XPDO_LOG_LEVEL_INFO,$modx->lexicon('package_install_info_start',array('signature' => $_REQUEST['signature'] )));

// find package
if (!isset($_REQUEST['signature'])) {
    return $modx->error->failure($modx->lexicon('package_err_ns'));
}
$package= $modx->getObject('transport.modTransportPackage',$_REQUEST['signature']);
if ($package == null) {
    return $modx->error->failure(sprintf($modx->lexicon('package_err_nfs'),$_REQUEST['signature']));
}

$modx->log(XPDO_LOG_LEVEL_INFO,$modx->lexicon('package_install_info_found'));

// install package
$installed = $package->install();

if (!$installed) {
    $msg = $modx->lexicon('package_err_install',array('signature' => $package->get('signature')));
    $modx->log(XPDO_LOG_LEVEL_ERROR,$msg);
    return $modx->error->failure($msg);
} else {
    $msg = $modx->lexicon('package_install_info_success',array('signature' => $package->get('signature')));
    $modx->log(XPDO_LOG_LEVEL_WARN,$msg);
    return $modx->error->success($msg);
}
return $modx->error->failure($modx->lexicon('package_err_install_gen'));