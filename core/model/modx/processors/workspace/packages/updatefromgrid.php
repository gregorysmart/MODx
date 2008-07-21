<?php
/**
 * @package modx
 * @subpackage processors.workspace.packages
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('workspace');

$_DATA = $modx->fromJSON($_POST['data']);

$package = $modx->getObject('transport.modTransportPackage',array(
    'signature' => $_DATA['signature'],
));

$package->fromArray($_DATA);
if ($package->save() === false) {
    $modx->error->failure($modx->lexicon('package_err_save'));
}
$modx->error->success();