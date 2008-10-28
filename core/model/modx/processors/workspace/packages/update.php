<?php
/**
 * @package modx
 * @subpackage processors.workspace.packages
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('workspace');

if (!$modx->hasPermission('packages')) return $modx->error->failure($modx->lexicon('permission_denied'));

return $modx->error->failure('Not yet implemented.');

$package = $modx->getObject('transport.modTransportPackage',$_REQUEST['signature']);
if ($package == null) {
    $modx->log(XPDO_LOG_LEVEL_ERROR,$modx->lexicon('package_err_nf'));
    return $modx->error->failure();
}

if ($package->provider != 0) {
    $provider = $package->getOne('Provider');
    if ($provider == null) {
        $modx->log(MODX_LOG_LEVEL_ERROR,$modx->lexicon('provider_err_nf'));
        return $modx->error->failure();
    }
} else {
    $modx->log(XPDO_LOG_LEVEL_ERROR,$modx->lexicon('package_update_err_provider_nf'));
    return $modx->error->failure();
}

$modx->log(MODX_LOG_LEVEL_INFO,$modx->lexicon('package_update_info_provider_scan',array('provider' => $provider->get('name'))));
$downloadedPackages = $provider->scanForPackages();
if (empty($downloadedPackages)) {
    $modx->log(MODX_LOG_LEVEL_ERROR,$modx->lexicon('package_update_err_provider_empty'));
    return $modx->error->failure();
}

$packageSignature = explode('-',$package->get('signature'));
list($packageName,$packageVersion,$packageRelease) = explode('-',$package->get('signature'));

$updatePackage = null;
$updatable = false;
$found = false;
foreach ($downloadedPackages as $p) {
    list($updateName,$updateVersion,$updateRelease) = explode('-',$p['signature']);

    if ($updateName == $packageName) {
        $found = true;
        $modx->log(MODX_LOG_LEVEL_INFO,$modx->lexicon('package_update_info_diff'));

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
    $modx->log(MODX_LOG_LEVEL_ERROR,'The package could not be found at the provider: '.$provider->get('name'));
    return $modx->error->failure();
}
if ($updatable === false) {
    $modx->log(MODX_LOG_LEVEL_ERROR,'Your package is already up-to-date at: '.$package->get('signature'));
    return $modx->error->failure();
}


$modx->log(MODX_LOG_LEVEL_ERROR,print_r($updatePackage,true));

return $modx->error->success();