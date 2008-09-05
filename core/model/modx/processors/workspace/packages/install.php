<?php
/**
 * @package modx
 * @subpackage processors.workspace.packages
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('workspace');

$oldLogTarget = $modx->logTarget;
if (isset($_POST['register']) && !empty($_POST['register']) && preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $_POST['register'])) {
    if (isset($_POST['topic']) && !empty($_POST['topic'])) {
        $register = trim($_POST['register']);
        $topic = trim($_POST['topic']);

        if ($modx->getService('registry', 'registry.modRegistry')) {
            $modx->registry->addRegister($register, 'registry.modFileRegister', array('directory' => $register));
            if ($modx->registry->$register->connect()) {
                $modx->registry->$register->subscribe($topic);
                $modx->registry->$register->setCurrentTopic($topic);
                $modx->setLogTarget($modx->registry->$register);
            }
        }
    }
}

$modx->setLogLevel(XPDO_LOG_LEVEL_INFO);
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

    if ($modx->logTarget !== $oldLogTarget) $modx->setLogTarget($oldLogTarget);
    $modx->error->failure($modx->lexicon('package_err_install',array('signature' => $package->get('signature'))));
} else {
    $msg = sprintf($modx->lexicon('package_installed'),$package->get('signature'));
    $modx->log(XPDO_LOG_LEVEL_INFO,$msg);

    if ($modx->logTarget !== $oldLogTarget) $modx->setLogTarget($oldLogTarget);
    $modx->error->success($msg);
}
$modx->error->failure($modx->lexicon('package_err_install_gen'));