<?php
/**
 * @package modx
 * @subpackage processors.context
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('context');

if (!$modx->hasPermission('edit_context')) $error->failure($modx->lexicon('permission_denied'));

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['sort'])) $_REQUEST['sort'] = 'key';
if (!isset($_REQUEST['dir'])) $_REQUEST['dir'] = 'ASC';
if ($_REQUEST['sort'] == 'key_link') $_REQUEST['sort'] = 'key';

$c = $modx->newQuery('modContext');
$c->select($modx->getSelectColumns('modContext'));
$c->sortby('`' . $_REQUEST['sort'] . '`', $_REQUEST['dir']);
$c->limit($_REQUEST['limit'], $_REQUEST['start']);

$collection = $modx->getCollection('modContext', $c);
$count = $modx->getCount('modContext');
$actions = $modx->request->getAllActionIDs();

$list = array();
foreach ($collection as $key => $object) {
	$la = array_merge(
       $object->toArray(),
       array('key_link' => '<a href="index.php?a='.$actions['context/update'].'&key='.$key.'" title="' . $modx->lexicon('click_to_edit_title') . '">' . $key . '</a>')
    );
    $la['menu'] = array(
        array(
            'text' => $modx->lexicon('context_update'),
            'handler' => 'this.update'
        ),
    );
    if (!in_array($key,array('connector','mgr','web'))) {
        array_push($la['menu'],
            '-',
            array(
                'text' => $modx->lexicon('context_remove'),
                'handler' => 'this.remove.createDelegate(this,["context_remove_confirm"])',
            )
        );
    }
    $list[]= $la;
}
$this->outputArray($list,$count);