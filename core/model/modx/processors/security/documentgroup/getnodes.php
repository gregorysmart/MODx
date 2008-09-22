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
	$docs = $g->getDocumentsIn();
	foreach ($docs as $doc) {
		$da[] = array(
			'text' => $doc->pagetitle,
			'id' => 'n_'.$doc->id,
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