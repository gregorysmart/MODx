<?php
/**
 * Uninstall a package
 *
 * @param string $signature The signature of the package.
 *
 * @package modx
 * @subpackage processors.workspace.packages
 */
$modx->lexicon->load('workspace');

if (!$modx->hasPermission('packages')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_REQUEST['signature'])) {
    return $modx->error->failure($modx->lexicon('package_err_ns'));
}
$modx->log(MODX_LOG_LEVEL_INFO,$modx->lexicon('package_uninstall_info_find',array('signature' => $_REQUEST['signature'])));

$package = $modx->getObject('transport.modTransportPackage', $_REQUEST['signature']);
if ($package == null) {
    return $modx->error->failure(sprintf($modx->lexicon('package_err_nfs'),$_REQUEST['signature']));
}

$modx->log(MODX_LOG_LEVEL_INFO,$modx->lexicon('package_uninstall_info_prep'));

$options = array(
    'preexisting_mode' => $_POST['preexisting_mode'],
);
if ($package->uninstall($options) == false) {
    return $modx->error->failure(sprintf($modx->lexicon('package_err_uninstall'),$package->getPrimaryKey()));
}

/* empty cache */
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

$modx->log(MODX_LOG_LEVEL_WARN,$modx->lexicon('package_uninstall_info_success',array('signature' => $package->get('signature'))));
return $modx->error->success();