<?php
/**
 * @package modx
 * @subpackage processors.workspace.namespace
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('workspace','namespace');

if (isset($_REQUEST['limit'])) $limit = true;
if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'name';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';


$c = $modx->newQuery('modNamespace');
if (isset($_REQUEST['name']) && $_REQUEST['name'] != '') {
    $c->where(array('name:LIKE' => '%'.$_REQUEST['name'].'%'));
}

$c->sortby($_REQUEST['sort'],$_REQUEST['dir']);
if ($limit) $c->limit($_REQUEST['limit'],$_REQUEST['start']);
$namespaces = $modx->getCollection('modNamespace',$c);
$count = $modx->getCount('modNamespace');

$ps = array();
foreach ($namespaces as $namespace) {
    $pa = $namespace->toArray();
    $pa['menu'] = array(
        /* left out for future development
         * array(
            'text' => $modx->lexicon('namespace_update'),
            'handler' => array( 'xtype' => 'window-namespace-update' ),
        ),
        '-',*/
        array(
            'text' => $modx->lexicon('namespace_remove'),
            'handler' => 'this.remove.createDelegate(this,["namespace_remove_confirm"])',
        )
    );
    $ps[] = $pa;
}

$this->outputArray($ps,$count);