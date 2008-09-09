<?php
/**
 * @package modx
 * @subpackage processors.workspace.packages
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('workspace');

$modx->log(XPDO_LOG_LEVEL_INFO,'Grabbing package to remove...');
$package = $modx->getObject('transport.modTransportPackage', $_REQUEST['signature']);
if ($package == null) $modx->error->failure($modx->lexicon('package_err_nfs',array('signature' => $_REQUEST['signature'])));

$modx->log(XPDO_LOG_LEVEL_INFO,'Successfully grabbed package. Now attempting to remove...');
if ($package->remove() == false) {
    $modx->log(XPDO_LOG_LEVEL_ERROR,$modx->lexicon('package_err_remove'));
    $modx->error->failure($modx->lexicon('package_err_remove',array('signature' => $package->getPrimaryKey())));
}

$modx->log(MODX_LOG_LEVEL_WARN,'Package successfully removed.');
$modx->error->success();