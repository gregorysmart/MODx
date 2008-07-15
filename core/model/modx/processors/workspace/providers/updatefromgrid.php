<?php
/**
 * @package modx
 * @subpackage processors.workspace.providers
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('workspace');

$_DATA = $modx->fromJSON($_POST['data']);

if (!isset($_DATA['id'])) $modx->error->failure($modx->lexicon('provider_err_ns'));
$provider = $modx->getObject('transport.modTransportProvider',$_DATA['id']);
if ($provider == null) {
    $modx->error->failure(sprintf($modx->lexicon('provider_err_nfs'),$_DATA['id']));
}

if (!isset($_DATA['name']) || $_DATA['name'] == '') {
    $modx->error->failure($modx->lexicon('provider_err_ns_name'));
}
if (!isset($_DATA['service_url']) || $_DATA['service_url'] == '') {
    $modx->error->failure($modx->lexicon('provider_err_ns_url'));
}

$provider->set('name',$_DATA['name']);
$provider->set('service_url',$_DATA['service_url']);
$provider->set('description',$_DATA['description']);

if (!$provider->save()) $modx->error->failure($modx->lexicon('provider_err_save'));

$modx->error->success();