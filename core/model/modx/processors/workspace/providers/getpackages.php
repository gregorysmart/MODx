<?php
/**
 * @package modx
 * @subpackage processors.workspace.providers
 */
$modx->lexicon->load('workspace');

if (!$modx->hasPermission('providers')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'name';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';


$provider = $modx->getObject('transport.modTransportProvider',$_REQUEST['provider']);
if ($provider == null) {
    return $modx->error->failure($modx->lexicon('provider_err_nf'));
}
$map = $provider->scanForPackages();

return $modx->error->success('',$map);