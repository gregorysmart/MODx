<?php
/**
 * @package modx
 * @subpackage processors.security.documentgroup
 */

require_once MODX_PROCESSORS_PATH.'index.php';
if (!$modx->hasPermission('access_permissions')) $modx->error->failure($modx->lexicon('permission_denied'));


$_REQUEST['id'] = !isset($_REQUEST['id']) ? 0 : str_replace('n_dg_','',$_REQUEST['id']);

$g = $modx->getObject('modResourceGroup',$_REQUEST['id']);
$groups = $modx->getCollection('modResourceGroup');

$da = array();

if ($g == null) {
	foreach ($groups as $group) {
		$da[] = array(
			'text' => $group->name,
			'id' => 'n_dg_'.$group->id,
			'leaf' => 0,
			'type' => 'documentgroup',
			'cls' => 'folder',
		);
	}
} else {
	$ugs = $g->getUserGroupsIn();
	foreach ($ugs as $ug) {
		$da[] = array(
			'text' => $ug->name,
			'id' => 'n_ug_'.$ug->id,
			'leaf' => 1,
			'type' => 'usergroup',
			'cls' => '',
		);
	}
}

echo $modx->toJSON($da);