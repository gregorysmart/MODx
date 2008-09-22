<?php
/**
 * @package modx
 * @subpackage processors.workspace.providers
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('workspace');

if (!$modx->hasPermission('providers')) $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['name']) || $_POST['name'] == '') {
    $modx->error->failure($modx->lexicon('provider_err_ns_name'));
}
if (!isset($_POST['service_url']) || $_POST['service_url'] == '') {
    $modx->error->failure($modx->lexicon('provider_err_ns_url'));
}

if (!isset($_POST['id'])) $modx->error->failure($modx->lexicon('provider_err_ns'));
$provider = $modx->getObject('transport.modTransportProvider',$_POST['id']);
if ($provider == null) $modx->error->failure(sprintf($modx->lexicon('provider_err_nfs'),$_POST['id']));

// TODO: Check for a valid connection to the provisioner.

$provider->set('name',$_POST['name']);
$provider->set('description',$_POST['description']);
$provider->set('service_url',$_POST['service_url']);

if (!$provider->save()) $modx->error->failure($modx->lexicon('provider_err_save'));

$modx->error->success('',$provider);