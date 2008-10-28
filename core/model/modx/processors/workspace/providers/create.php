<?php
/**
 * @package modx
 * @subpackage processors.workspace.providers
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('workspace');

if (!$modx->hasPermission('providers')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['name']) || $_POST['name'] == '') {
    return $modx->error->failure($modx->lexicon('provider_err_ns_name'));
}
if (!isset($_POST['service_url']) || $_POST['service_url'] == '') {
    return $modx->error->failure($modx->lexicon('provider_err_ns_url'));
}

/* TODO: Check for a valid connection to the provider. */

$provider = $modx->newObject('transport.modTransportProvider');
$provider->set('name',$_POST['name']);
$provider->set('description',$_POST['description']);
$provider->set('service_url',$_POST['service_url']);

if ($provider->save() == false) {
    return $modx->error->failure($modx->lexicon('provider_err_save'));
}

return $modx->error->success('',$provider);