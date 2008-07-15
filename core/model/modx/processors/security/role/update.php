<?php
/**
 * @package modx
 * @subpackage processors.security.role
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('role');

if (!$modx->hasPermission('save_role')) $error->failure($modx->lexicon('permission_denied'));

$role = $modx->getObject('modUserGroupRole',$_REQUEST['id']);
if ($role == null) $error->failure($modx->lexicon('role_err_not_found'));

if ($_REQUEST['name'] == '')
	$error->failure($modx->lexicon('role_err_not_specified_name'));

$role->fromArray($_REQUEST);
if (!$role->save()) {
	$error->failure($modx->lexicon('role_err_save'));
}

// log manager action
$modx->logManagerAction('role_update','modUserGroupRole',$role->id);

$error->success();