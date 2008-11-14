<?php
/**
 * Update a user group
 *
 * @param integer $id The ID of the user group
 * @param string $name The new name of the user group
 *
 * @package modx
 * @subpackage processors.security.group
 */
$modx->lexicon->load('user');

if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['id'])) {
    return $modx->error->failure($modx->lexicon('user_group_err_not_specified'));
}
$ug = $modx->getObject('modUserGroup',$_POST['id']);
if ($ug == null) return $modx->error->failure($modx->lexicon('user_group_err_not_found'));

$ug->set('name',$_POST['name']);

if ($ug->save() === false) {
    return $modx->error->failure($modx->lexicon('user_group_err_save'));
}

/* log manager action */
$modx->logManagerAction('save_user_group','modUserGroup',$ug->get('id'));

return $modx->error->success();