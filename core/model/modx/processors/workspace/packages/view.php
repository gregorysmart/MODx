<?php
/**
 * @package modx
 * @subpackage processors.workspace.packages
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('workspace');

if (!$modx->hasPermission('packages')) $modx->error->failure($modx->lexicon('permission_denied'));

$collection= array ();
if (isset($_REQUEST['id']) && $objId= $_REQUEST['id']) {
    if ($package = $modx->getObject('transport.modTransportPackage', $objId)) {
        $oa = $package->toArray();
        $installed = $package->get('installed');
        $oa['installed'] = $installed == null ? $modx->lexicon('no') : $installed;
        $collection[]= $oa;
    }
}
$modx->error->success('', $collection);