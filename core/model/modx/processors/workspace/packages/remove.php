<?php
/**
 * @package modx
 * @subpackage processors.workspace.packages
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('workspace');

$package = $modx->getObject('transport.modTransportPackage', $_REQUEST['signature']);
if ($package == null) $modx->error->failure(sprintf($modx->lexicon('package_err_nfs'),$_REQUEST['signature']));

if (!$package->remove()) {
    $modx->error->failure(sprintf($modx->lexicon('package_err_remove'),$package->getPrimaryKey()));
}

$modx->error->success();