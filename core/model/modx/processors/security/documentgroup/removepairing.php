<?php
/**
 * @package modx
 * @subpackage processors.security.documentgroup
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('user');

if (!$modx->hasPermission('access_permissions')) $modx->error->failure($modx->lexicon('permission_denied'));

$ugdg = $modx->getObject('modAccessResourceGroup',array(
	'target' => $_POST['dg_id'],
	'principal' => $_POST['ug_id'],
	'principal_class' => 'modUserGroup',
));
if ($ugdg == null) $modx->error->failure($modx->lexicon('user_group_document_group_err_not_found'));

if ($ugdg->remove() == false) {
    $modx->error->failure($modx->lexicon('user_group_document_group_err_remove'));
}

$modx->error->success();