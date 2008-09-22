<?php
/**
 * @package modx
 * @subpackage processors.security.role
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('role');

if (!$modx->hasPermission(array('access_permissions' => true, 'delete_role' => true))) $error->failure($modx->lexicon('permission_denied'));

$role = $modx->getObject('modUserGroupRole',$_REQUEST['id']);
if ($role == null) $error->failure($modx->lexicon('role_err_not_found'));

// don't delete the Member or Super User roles
if ($role->name == 'Member' || $role->name == 'Super User') $error->failure($modx->lexicon('role_err_remove_admin'));

//don't delete if this role is assigned
$cc = $modx->newQuery('modUserGroupMember');
$cc = $cc->where(array('role' => $role->id));
if($modx->getCount('modUserProfile',$cc) > 0) $error->failure($modx->lexicon('role_err_has_users'));

if(!$role->remove()) $error->failure($modx->lexicon('role_err_save'));

// log manager action
$modx->logManagerAction('role_delete','modUserGroupRole',$role->id);

$error->success();