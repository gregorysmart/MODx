<?php
/**
 * @package modx
 * @subpackage processors.workspace.packages
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('workspace');

if (!$modx->hasPermission('packages')) $modx->error->failure($modx->lexicon('permission_denied'));

$modx->log(XPDO_LOG_LEVEL_INFO,'Grabbing package to remove...');
$package = $modx->getObject('transport.modTransportPackage', $_REQUEST['signature']);
if ($package == null) $modx->error->failure($modx->lexicon('package_err_nfs',array('signature' => $_REQUEST['signature'])));

$modx->log(XPDO_LOG_LEVEL_INFO,'Successfully grabbed package. Now attempting to remove actual file...');

$f = $modx->config['core_path'].'packages/'.$package->signature.'.transport.zip';
if (!file_exists($f)) {
    $modx->log(XPDO_LOG_LEVEL_ERROR,'Transport file was not found and could not be removed from the core/packages directory.');
} else if (!@unlink($f)) {
    $modx->log(XPDO_LOG_LEVEL_ERROR,'Transport file was unable to be removed, check your permissions.');
}

if ($package->remove() == false) {
    $modx->log(XPDO_LOG_LEVEL_ERROR,$modx->lexicon('package_err_remove'));
    $modx->error->failure($modx->lexicon('package_err_remove',array('signature' => $package->getPrimaryKey())));
}

$modx->log(MODX_LOG_LEVEL_WARN,'Package successfully removed.');
$modx->error->success();