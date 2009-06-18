<?php
/**
 * Gets all action nodes for a namespace
 *
 * @package modx
 * @subpackage processors.system.action
 */
$list = array();

$c = $modx->newQuery('modAction');
$c->leftJoin('modAction','Children');
$c->select('
    `modAction`.*,
    COUNT(`Children`.`id`) AS `childrenCount`
');
$c->where(array(
    'modAction.parent' => 0,
    'modAction.namespace' => $pk,
));
$c->groupby('modAction.id');
$c->sortby('modAction.controller','ASC');

$actions = $modx->getCollection('modAction',$c);

foreach ($actions as $action) {
    $list[] = array(
        'text' => $action->get('controller').' ('.$action->get('id').')',
        'id' => 'n_action_'.$action->get('id'),
        'pk' => $action->get('id'),
        'leaf' => $action->get('childrenCount') <= 0,
        'cls' => 'action',
        'type' => 'action',
        'data' => $action->toArray(),
        'menu' => array(
            'items' => array(
                array(
                    'text' => $modx->lexicon('action_update'),
                    'handler' => 'function(itm,e) {
                        this.updateAction(itm,e);
                    }',
                ),'-',array(
                    'text' => $modx->lexicon('action_create_here'),
                    'handler' => 'function(itm,e) {
                        this.createAction(itm,e);
                    }',
                ),'-',array(
                    'text' => $modx->lexicon('action_remove'),
                    'handler' => 'function(itm,e) {
                        this.removeAction(itm,e);
                    }',
                ),
            ),
        ),
    );
}
return $list;