<?php
/**
 * @package modx
 * @subpackage processors.workspace.packages
 */
$modx->lexicon->load('workspace');

if (!$modx->hasPermission('packages')) return $modx->error->failure($modx->lexicon('permission_denied'));

$_DATA = $modx->fromJSON($_POST['data']);

$package = $modx->getObject('transport.modTransportPackage',array(
    'signature' => $_DATA['signature'],
));

$package->fromArray($_DATA);
if ($package->save() === false) {
    return $modx->error->failure($modx->lexicon('package_err_save'));
}
return $modx->error->success();