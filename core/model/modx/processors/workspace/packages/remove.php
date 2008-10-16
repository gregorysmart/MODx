<?php
/**
 * @package modx
 * @subpackage processors.workspace.packages
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('workspace');

/* set proper force value from checkbox */
if (!isset($_POST['force']) || $_POST['force'] !== 'true') $_POST['force'] = false;

if (!$modx->hasPermission('packages')) $modx->error->failure($modx->lexicon('permission_denied'));

/* get package */
$modx->log(XPDO_LOG_LEVEL_INFO,$modx->lexicon('package_remove_info_gpack'));
$package = $modx->getObject('transport.modTransportPackage', $_REQUEST['signature']);
if ($package == null) $modx->error->failure($modx->lexicon('package_err_nfs',array('signature' => $_REQUEST['signature'])));

$modx->log(XPDO_LOG_LEVEL_INFO,$modx->lexicon('package_remove_info_tzip_start'));

$cacheManager = $modx->getCacheManager();

/* remove transport zip */
$f = $modx->config['core_path'].'packages/'.$package->signature.'.transport.zip';
if (!file_exists($f)) {
    $modx->log(XPDO_LOG_LEVEL_ERROR,$modx->lexicon('package_remove_err_tzip_nf'));
} else if (!@unlink($f)) {
    $modx->log(XPDO_LOG_LEVEL_ERROR,$modx->lexicon('package_remove_err_tzip'));
} else {
    $modx->log(XPDO_LOG_LEVEL_INFO,$modx->lexicon('package_remove_info_tzip'));
}
$modx->log(XPDO_LOG_LEVEL_INFO,$modx->lexicon('package_remove_info_tdir_start'));

/* remove transport dir */
$f = $modx->config['core_path'].'packages/'.$package->signature.'/';
if (!file_exists($f)) {
    $modx->log(XPDO_LOG_LEVEL_ERROR,$modx->lexicon('package_remove_err_tdir_nf'));
} else if (!$cacheManager->deleteTree($f,true,false,array())) {
    $modx->log(XPDO_LOG_LEVEL_ERROR,$modx->lexicon('package_remove_err_tdir'));
} else {
    $modx->log(XPDO_LOG_LEVEL_INFO,$modx->lexicon('package_remove_info_tdir'));
}

/* remove transport package */
if ($package->remove($_POST['force']) == false) {
    $modx->log(XPDO_LOG_LEVEL_ERROR,$modx->lexicon('package_err_remove'));
    $modx->error->failure($modx->lexicon('package_err_remove',array('signature' => $package->getPrimaryKey())));
}

$modx->log(MODX_LOG_LEVEL_WARN,$modx->lexicon('package_remove_info_success'));
$modx->error->success();