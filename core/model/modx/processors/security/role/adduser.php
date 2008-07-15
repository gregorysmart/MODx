<?php
/**
 * @package modx
 * @subpackage processors.security.role
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('user','role');

if (!$modx->hasPermission('save_role')) $error->failure($modx->lexicon('permission_denied'));

$role = $modx->getObject('modUserGroupRole',$_REQUEST['role']);
if ($role == null) $error->failure($modx->lexicon('role_err_not_found'));

$user = $modx->getObject('modUser',$_REQUEST['user']);
if ($user == null) $error->failure($modx->lexicon('user_err_nf'));

// TODO: Adding a user to a role...?
$modx->error->failure('Not yet implemented.');

$error->success();