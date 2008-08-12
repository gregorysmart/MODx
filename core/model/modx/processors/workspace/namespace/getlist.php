<?php
/**
 * @package modx
 * @subpackage processors.workspace.namespace
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('workspace');

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'name';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';

$c = $modx->newQuery('modNamespace');
$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);
$namespaces = $modx->getCollection('modNamespace',$c);
$count = $modx->getCount('modNamespace');

$ps = array();
foreach ($namespaces as $namespace) {
    $pa = $namespace->toArray();
    $pa['menu'] = array(
        array(
            'text' => $modx->lexicon('namespace_update'),
            'handler' => array( 'xtype' => 'window-namespace-update' ),
        ),
        '-',
        array(
            'text' => $modx->lexicon('namespace_remove'),
            'handler' => 'this.remove.createDelegate(this,["namespace_confirm_remove"])',
        )
    );
    $ps[] = $pa;
}

$this->outputArray($ps,$count);