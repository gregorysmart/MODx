<?php
/**
 * Update a role from a POST request
 *
 * @package modx
 * @subpackage processors.security.role
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('role');

if (!$modx->hasPermission(array('access_permissions' => true, 'save_role' => true))) {
    $modx->error->failure($modx->lexicon('permission_denied'));
}

$role = $modx->getObject('modUserGroupRole',$_POST['id']);
if ($role == null) $error->failure($modx->lexicon('role_err_not_found'));

if ($_POST['name'] == '') {
	$modx->error->failure($modx->lexicon('role_err_not_specified_name'));
}

$role->fromArray($_POST);
if ($role->save() == false) {
	$modx->error->failure($modx->lexicon('role_err_save'));
}

/* log manager action */
$modx->logManagerAction('role_update','modUserGroupRole',$role->get('id'));

$modx->error->success();