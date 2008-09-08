<?php
/**
 * @package modx
 * @subpackage processors.workspace.packages
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('workspace');

if (isset($_POST['register']) && isset($_POST['topic'])) {
    if ($modx->getService('registryhandler','registry.modRegisterHandler')) {
        $modx->registryhandler->load($_POST['register'],$_POST['topic']);
    }
}

$modx->log(XPDO_LOG_LEVEL_INFO,$modx->lexicon('package_install_beginning',array('signature' => $_REQUEST['signature'] )));

// find package
if (!isset($_REQUEST['signature'])) {
    $modx->error->failure($modx->lexicon('package_err_ns'));
}
$package= $modx->getObject('transport.modTransportPackage',$_REQUEST['signature']);
if ($package == null) {
    $modx->error->failure(sprintf($modx->lexicon('package_err_nfs'),$_REQUEST['signature']));
}

$modx->log(XPDO_LOG_LEVEL_INFO,$modx->lexicon('package_install_found'));

// install package
$installed = $package->install();

if (!$installed) {
    $modx->log(XPDO_LOG_LEVEL_ERROR,$modx->lexicon('package_err_install',array('signature' => $package->get('signature'))));

    if ($modx->registryhandler) $modx->registryhandler->unload();
    $modx->error->failure($modx->lexicon('package_err_install',array('signature' => $package->get('signature'))));
} else {
    $msg = sprintf($modx->lexicon('package_installed'),$package->get('signature'));
    $modx->log(XPDO_LOG_LEVEL_WARN,$msg);

    if ($modx->registryhandler) $modx->registryhandler->unload();
    $modx->error->success($msg);
}
$modx->error->failure($modx->lexicon('package_err_install_gen'));