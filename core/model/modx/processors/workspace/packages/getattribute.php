<?php
/**
 * @package modx
 * @subpackage processors.workspace.packages
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('workspace');

if (!$modx->hasPermission('packages')) $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_REQUEST['signature'])) $modx->error->failure($modx->lexicon('package_err_ns'));
$package = $modx->getObject('transport.modTransportPackage',$_REQUEST['signature']);
if ($package == null) $modx->error->failure($modx->lexicon('package_err_nf'));

$transport = $package->getTransport();
if ($transport) {
    $attr = $transport->getAttribute($_REQUEST['attr']);
} else {
    $modx->error->failure($modx->lexicon('package_err_nf'));
}

$modx->error->success('',array('attr' => $attr));