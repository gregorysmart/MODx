<?php
/**
 * Sorts the resource tree
 *
 * @param string $data The encoded tree data
 *
 * @package modx
 * @subpackage processors.layout.tree.resource
 */
$modx->lexicon->load('resource');

if (!$modx->hasPermission('save_document')) return $modx->error->failure($modx->lexicon('access_denied'));

$data = urldecode($_POST['data']);
$data = $modx->fromJSON($data);
$nodes = array();
getNodesFormatted($nodes,$data);

/* readjust cache */
foreach ($nodes as $ar_node) {
    $node = $modx->getObject('modResource',$ar_node['id']);
    if ($node == null) continue;
    $old_parent_id = $node->get('parent');

    if ($old_parent_id != $ar_node['parent']) {
        /* get new parent, if invalid, skip, unless is root */
        if ($ar_node['parent'] != 0) {
            $parent = $modx->getObject('modResource',$ar_node['parent']);
            if ($parent == null) continue;
        }

        /* save new parent */
        $node->set('parent',$ar_node['parent']);
    }
    $old_context_key = $node->get('context_key');
    if ($old_context_key != $ar_node['context']) {
        $node->set('context_key',$ar_node['context']);
        changeChildContext($node, $ar_node['context']);
    }
    $node->set('menuindex',$ar_node['order']);
    $node->save();
}

/* clear cache */
$cacheManager = $modx->getCacheManager();
$cacheManager->clearCache();

return $modx->error->success();

function getNodesFormatted(&$ar_nodes,$cur_level,$parent = 0) {
    $order = 0;
    foreach ($cur_level as $id => $children) {
        $ar = explode('_',$id);
        if ($ar[1] != '0') {
            $par = explode('_',$parent);
            $ar_nodes[] = array(
                'id' => $ar[1],
                'context' => $par[0],
                'parent' => $par[1],
                'order' => $order,
            );
            $order++;
        }
        getNodesFormatted($ar_nodes,$children,$id);
    }
}

function changeChildContext(&$node, $context) {
    foreach ($node->getMany('Children') as $child) {
        $child->set('context_key', $context);
        changeChildContext($child, $context);
        $child->save();
    }
}