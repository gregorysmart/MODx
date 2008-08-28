<?php
/**
 * @package modx
 * @subpackage processors.workspace.providers
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('workspace');

$_package_cache = $modx->config['core_path'].'packages/';
$packages = $modx->fromJSON($_POST['packages']);

foreach ($packages as $package) {
    $pkg = $modx->newObject('transport.modTransportPackage');
    $pkg->set('signature',$package['signature']);
    $pkg->set('state',1);
    $pkg->set('workspace',1);
    $pkg->transferPackage($package['location'],$_package_cache);
    $pkg->set('created',date('Y-m-d h:i:s'));
    
    if (!$pkg->save()) {
        $modx->log(MODX_LOG_LEVEL_ERROR,'Could not create transport package: '.$pkg->get('signature'));
    }
}

$modx->error->success();