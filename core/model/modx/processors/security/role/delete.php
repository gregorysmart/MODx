<?php
/**
 * Removes a role.
 *
 * @package modx
 * @subpackage processors.security.role
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('role');

if (!$modx->hasPermission(array('access_permissions' => true, 'delete_role' => true))) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}

$role = $modx->getObject('modUserGroupRole',$_REQUEST['id']);
if ($role == null) return $modx->error->failure($modx->lexicon('role_err_not_found'));

/* don't delete the Member or Super User roles */
if ($role->get('name') == 'Member' || $role->get('name') == 'Super User') {
    return $modx->error->failure($modx->lexicon('role_err_remove_admin'));
}

/* don't delete if this role is assigned */
$cc = $modx->newQuery('modUserGroupMember');
$cc = $cc->where(array('role' => $role->get('id')));
if ($modx->getCount('modUserProfile',$cc) > 0) {
    return $modx->error->failure($modx->lexicon('role_err_has_users'));
}

if ($role->remove() == false) {
    return $modx->error->failure($modx->lexicon('role_err_save'));
}

/* log manager action */
$modx->logManagerAction('role_delete','modUserGroupRole',$role->get('id'));

return $modx->error->success();