<?php
/**
 * @package modx
 * @subpackage processors.system.action
 */
$modx->lexicon->load('action','menu');

if (!isset($_REQUEST['start'])) $_REQUEST['start'] = 0;
if (!isset($_REQUEST['limit'])) $_REQUEST['limit'] = 10;
if (!isset($_REQUEST['id'])) $_REQUEST['id'] = 'n_0';

$ar = explode('_',$_REQUEST['id']);
$type = $ar[1];
$id = $ar[2];


/* contexts */
if ($type == 'root') {
    $contexts = $modx->getCollection('modContext');

    $cs = array();
    foreach ($contexts as $context) {
        $cs[] = array(
            'text' => $context->get('key'),
            'id' => 'n_context_'.$context->get('key'),
            'leaf' => false,
            'cls' => 'folder',
            'type' => 'context',
            'menu' => array(
                array(
                    'text' => $modx->lexicon('action_create_here'),
                    'handler' => 'this.create',
                ),
            ),
        );
    }

    return $modx->toJSON($cs);
    die();

/* root actions */
} else if ($type == 'context') {
    $c = $modx->newQuery('modAction');
    $c->where(array(
        'parent' => 0,
        'context_key' => $id,
    ));
    $c->sortby('controller','ASC');
    $c->limit($_REQUEST['limit'],$_REQUEST['start']);

    $actions = $modx->getCollection('modAction',$c);

    $cc = $modx->newQuery('modAction');
    $cc->where(array(
        'parent' => 0,
        'context_key' => $id,
    ));
    $count = $modx->getCount('modAction',$cc);

    $as = array();
    foreach ($actions as $action) {
        $as[] = array(
            'text' => $action->get('controller').' ('.$action->get('id').')',
            'id' => 'n_action_'.$action->get('id'),
            'leaf' => false,
            'cls' => 'action',
            'type' => 'action',
            'menu' => array(
                array(
                    'text' => $modx->lexicon('action_update'),
                    'handler' => 'this.update',
                ),'-',array(
                    'text' => $modx->lexicon('action_create_here'),
                    'handler' => 'this.create',
                ),'-',array(
                    'text' => $modx->lexicon('action_remove'),
                    'handler' => 'this.remove',
                ),
            ),
        );
    }

    return $modx->toJSON($as);
    die();

/* subactions */
} else {
    $c = $modx->newQuery('modAction');
    $c->where(array(
        'parent' => $id,
    ));
    $c->sortby('controller','ASC');
    $c->limit($_REQUEST['limit'],$_REQUEST['start']);

    $actions = $modx->getCollection('modAction',$c);
    $cc = $modx->newQuery('modAction');
    $cc->where(array(
        'parent' => $id,
    ));
    $count = $modx->getCount('modAction',$cc);

    $as = array();
    foreach ($actions as $action) {
        $as[] = array(
            'text' => $action->get('controller').' ('.$action->get('id').')',
            'id' => 'n_action_'.$action->get('id'),
            'leaf' => 0,
            'cls' => 'action',
            'type' => 'action',
            'menu' => array(
                array(
                    'text' => $modx->lexicon('action_update'),
                    'handler' => 'this.update',
                ),'-',array(
                    'text' => $modx->lexicon('action_create_here'),
                    'handler' => 'this.create',
                ),'-',array(
                    'text' => $modx->lexicon('action_remove'),
                    'handler' => 'this.remove',
                ),
            ),
        );
    }

    return $modx->toJSON($as);
    die();
}
