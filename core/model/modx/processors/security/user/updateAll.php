<?php
/**
 * @package modx
 * @subpackage processors.security.user
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('user');

if (!$modx->hasPermission('save_user')) $error->failure($modx->lexicon('permission_denied'));

$_DATA = $modx->fromJSON($_POST['data']);

foreach ($_DATA as $userdata) {
	$user = $modx->getObject('modUser',$userdata['id']);
	if ($user == null) continue;

	$up = $user->getOne('modUserProfile');
	$up->set('gender',$userdata['gender']);
	$up->set('fullname',$userdata['fullname']);
	$up->set('blocked',$userdata['blocked']);
	$up->set('email',$userdata['email']);

	if (!$up->save()) $error->failure($modx->lexicon('user_err_save'));
}

$error->success();