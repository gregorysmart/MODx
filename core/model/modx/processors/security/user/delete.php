<?php
/**
 * @package modx
 * @subpackage processors.security.user
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('user');

if (!$modx->hasPermission(array('access_permissions' => true, 'delete_user' => true))) $modx->error->failure($modx->lexicon('permission_denied'));

$user = $modx->getObject('modUser',$_REQUEST['id']);
if ($user == null) $error->failure($modx->lexicon('user_err_nf'));


// check if we are deleting our own record
if($user->id == $modx->getLoginUserID())
	$error->failure($modx->lexicon('user_err_cannot_delete_self'));

// invoke OnBeforeUserFormDelete event
$modx->invokeEvent('OnBeforeUserFormDelete',array(
	'id' => $user->id,
));

// get and delete all user group pairs
$user->groups = $user->getMany('modUserGroupMember');
foreach ($user->groups as $group)
	$group->remove();

// get and delete user's profile
$user->profile = $user->getOne('modUserProfile');
$user->profile->remove();

// get and delete user's settings
$user->settings = $user->getMany('modUserSetting');
foreach ($user->settings as $setting)
	$setting->remove();

// now finally remove user
if (!$user->remove()) $error->failure($modx->lexicon('user_err_remove'));

// invoke OnManagerDeleteUser event
$modx->invokeEvent('OnManagerDeleteUser',array(
	'userid'	=> $user->id,
	'username'	=> $user->username,
));

// invoke OnUserFormDelete event
$modx->invokeEvent('OnUserFormDelete',array('id' => $user->id));

// log manager action
$modx->logManagerAction('user_delete','modUser',$user->id);

$error->success();