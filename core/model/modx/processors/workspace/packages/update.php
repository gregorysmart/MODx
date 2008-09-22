<?php
/**
 * @package modx
 * @subpackage processors.workspace.packages
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('workspace');

if (!$modx->hasPermission('packages')) $modx->error->failure($modx->lexicon('permission_denied'));

$package = $modx->getObject('transport.modTransportPackage',$_REQUEST['signature']);
if ($package == null) {
    $modx->log(XPDO_LOG_LEVEL_ERROR,$modx->lexicon('package_err_nf'));
    $modx->error->failure();
}

if ($package->provider != 0) {
    $provider = $package->getOne('Provider');
    if ($provider == null) {
        $modx->log(MODX_LOG_LEVEL_ERROR,$modx->lexicon('provider_err_nf'));
        $modx->error->failure();
    }
} else {
    $modx->log(XPDO_LOG_LEVEL_ERROR,'This package cannot be updated, because it was not installed from a provider.');
    $modx->error->failure();
}

$modx->log(MODX_LOG_LEVEL_INFO,'Scanning for package updates from provisioner: '.$provider->name);
$downloadedPackages = $provider->scanForPackages();
if (empty($downloadedPackages)) {
    $modx->log(MODX_LOG_LEVEL_ERROR,'No packages found in the specified provider.');
    $modx->error->failure();
}

$packageSignature = explode('-',$package->signature);
list($packageName,$packageVersion,$packageRelease) = explode('-',$package->signature);

$updatePackage = null;
$updatable = false;
$found = false;
foreach ($downloadedPackages as $p) {
    list($updateName,$updateVersion,$updateRelease) = explode('-',$p['signature']);

    if ($updateName == $packageName) {
        $found = true;
        $modx->log(MODX_LOG_LEVEL_INFO,'Found package. Checking for version difference.');

        // check version number
        if (!empty($updateVersion) && !empty($packageVersion)) {
            if (version_compare($updateVersion,$packageVersion,'>')) {
                $updatePackage = $p;
            } else if (version_compare($updateVersion,$packageVersion,'==')) {
                $modx->log(MODX_LOG_LEVEL_INFO,'Package version is the same. Checking release.');
                if ($updateRelease != $packageRelease) {
                    $modx->log(MODX_LOG_LEVEL_INFO,'Found update to: '.$p['signature']);
                    $updatable = true;
                }
            }
        }
    }
}
if ($found === false) {
    $modx->log(MODX_LOG_LEVEL_ERROR,'The package could not be found at the provider: '.$provider->name);
    $modx->error->failure();
}
if ($updatable === false) {
    $modx->log(MODX_LOG_LEVEL_ERROR,'Your package is already up-to-date at: '.$package->signature);
    $modx->error->failure();
}




$modx->log(MODX_LOG_LEVEL_ERROR,print_r($updatePackage,true));

$modx->error->success();