<?php
/**
 * @package modx
 * @subpackage processors.layout.tree.resource
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('resource');

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

		/* check if old parent has children left */
		$old_parent = $modx->getObject('modResource',$old_parent_id);
		if ($old_parent != null) $old_parent->checkChildren();

		/* change new parent to folder */
		if ($ar_node['parent'] != 0) {
			$parent->set('isfolder',1);
			$parent->save();
		}
	}
    $node->set('context_key',$ar_node['ctx']);
	$node->set('menuindex',$ar_node['order']);
	$node->save();
}

return $modx->error->success();

function getNodesFormatted(&$ar_nodes,$cur_level,$parent = 0) {
	$order = 0;
	foreach ($cur_level as $id => $children) {
        $ar = explode('_',$id);
        if ($ar[1] != '0') {
            $par = explode('_',$parent);
    		$ar_nodes[] = array(
    			'id' => $ar[1],
                'ctx' => $par[0],
    			'parent' => $par[1],
    			'order' => $order,
    		);
		    $order++;
        }
		getNodesFormatted($ar_nodes,$children,$id);
	}
}