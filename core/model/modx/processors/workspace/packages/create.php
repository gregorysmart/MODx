<?php
/**
 * @package modx
 * @subpackage processors.workspace.packages
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('workspace');

$package = $modx->newObject('transport.modTransportPackage');
$package->fromArray($_POST, '', true, false);
$package->set('state', 1);
if (!$package->save()) {
    $modx->error->failure($modx->lexicon('package_err_create'));
}
$error->success('', $package->get(array ('signature')));