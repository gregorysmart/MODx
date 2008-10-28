<?php
/**
 * @package modx
 * @subpackage processors.workspace.providers
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('workspace');

if (!$modx->hasPermission('providers')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'name';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

$c = $modx->newQuery('transport.modTransportProvider');
$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);
$providers = $modx->getCollection('transport.modTransportProvider',$c);
$count = $modx->getCount('transport.modTransportProvider');

$ps = array();
foreach ($providers as $provider) {
    $pa = $provider->toArray();
    $pa['menu'] = array(
        array(
            'text' => $modx->lexicon('provider_update'),
            'handler' => array( 'xtype' => 'window-provider-update' ),
        ),
        '-',
        array(
            'text' => $modx->lexicon('provider_remove'),
            'handler' => 'this.remove.createDelegate(this,["provider_confirm_remove"])',
        )
    );
    $ps[] = $pa;
}

return $this->outputArray($ps,$count);