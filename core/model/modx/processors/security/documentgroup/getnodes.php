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
			'text' => $group->get('name'),
			'id' => 'n_dg_'.$group->get('id'),
			'leaf' => 0,
			'type' => 'modResourceGroup',
			'cls' => 'folder',
            'menu' => array(
                array(
                    'text' => $modx->lexicon('create_document_group'),
                    'handler' => 'this.create',
                ),
                '-',
                array(
                    'text' => $modx->lexicon('delete_document_group'),
                    'handler' => 'this.remove',
                ),
            ),
		);
	}
} else {
	$resources = $g->getDocumentsIn();
	foreach ($resources as $resource) {
		$da[] = array(
			'text' => $resource->get('pagetitle'),
			'id' => 'n_'.$resource->get('id'),
			'leaf' => 1,
			'type' => 'modResource',
			'cls' => '',
            'menu' => array(
                array(
                    'text' => $modx->lexicon('delete_document_group_document'),
                    'handler' => 'this.removeResource',
                )
            ),
		);
	}
}

echo $modx->toJSON($da);