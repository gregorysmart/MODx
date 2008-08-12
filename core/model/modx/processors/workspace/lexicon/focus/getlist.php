<?php
/**
 * @package modx
 * @subpackage processors.workspace.lexicon
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('lexicon');

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['namespace'])) $_REQUEST['namespace'] = 'core';

$wa = array(
    'namespace' => $_REQUEST['namespace'],
);

$c = $modx->newQuery('modLexiconFocus');
$c->where($wa);
$c->sortby('name', 'ASC');
//$c->limit($_REQUEST['limit'],$_REQUEST['start']);
$foci = $modx->getCollection('modLexiconFocus',$c);
$count = $modx->getCount('modLexiconFocus',$wa);

$ps = array();
foreach ($foci as $focus) {
    $pa = $focus->toArray();

    $pa['menu'] = array(
        array(
            'text' => $modx->lexicon('focus_update'),
            'handler' => array( 'xtype' => 'window-lexicon-focus-update' ),
        ),
        '-',
        array(
            'text' => $modx->lexicon('focus_remove'),
            'handler' => 'this.remove.createDelegate(this,["focus_confirm_remove"])',
        ),
    );
    $ps[] = $pa;
}

$this->outputArray($ps,$count);