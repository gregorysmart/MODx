<?php
/**
 * @package modx
 * @subpackage processors.security.documentgroup
 */
$modx->lexicon->load('user');

if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));

$ugdg = $modx->getObject('modAccessResourceGroup',array(
	'target' => $_POST['dg_id'],
	'principal' => $_POST['ug_id'],
	'principal_class' => 'modUserGroup',
));
if ($ugdg == null) return $modx->error->failure($modx->lexicon('user_group_document_group_err_not_found'));

if ($ugdg->remove() == false) {
    return $modx->error->failure($modx->lexicon('user_group_document_group_err_remove'));
}

return $modx->error->success();