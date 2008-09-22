<?php
/**
 * @package modx
 * @subpackage processors.system.menu
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('action','menu');

if (!$modx->hasPermission('menus')) $modx->error->failure($modx->lexicon('permission_denied'));

$data = urldecode($_POST['data']);
$data = $modx->fromJSON($data);
$nodes = array();
getNodesFormatted($nodes,$data);

// readjust cache
foreach ($nodes as $ar_node) {
	$node = $modx->getObject('modMenu',$ar_node['id']);
	if ($node == NULL) continue;
	$old_parent_id = $node->parent;

	if ($old_parent_id != $ar_node['parent']) {
		// get new parent, if invalid, skip, unless is root
		if ($ar_node['parent'] != 0) {
			$parent = $modx->getObject('modMenu',$ar_node['parent']);
			if ($parent == NULL) continue;
		}

		// save new parent
		$node->set('parent',$ar_node['parent']);
	}
	$node->set('menuindex',$ar_node['order']);
	$node->save();
}

$error->success();

function getNodesFormatted(&$ar_nodes,$cur_level,$parent = 0) {
	$order = 0;
	foreach ($cur_level as $id => $children) {
		$id = explode('_',$id);
		$id = $id[1];
		$ar_nodes[] = array(
			'id' => $id,
			'parent' => $parent,
			'order' => $order,
		);
		$order++;
		getNodesFormatted($ar_nodes,$children,$id);
	}
}