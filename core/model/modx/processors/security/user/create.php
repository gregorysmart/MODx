<?php
/**
 * @package modx
 * @subpackage processors.security.user
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('user');

if (!$modx->hasPermission(array('access_permissions' => true, 'new_user' => true))) return $modx->error->failure($modx->lexicon('permission_denied'));

$user = $modx->newObject('modUser');

if ($_POST['newusername'] == '')
	$modx->error->addField('new_user_name',$modx->lexicon('user_err_not_specified_username'));

$newPassword= '';

require_once MODX_PROCESSORS_PATH.'security/user/_validation.php';

if ($_POST['passwordnotifymethod'] == 'e') {
	sendMailMessage($_POST['email'], $_POST['newusername'],$newPassword,$_POST['fullname']);
}

/* invoke OnBeforeUserFormSave event */
$modx->invokeEvent('OnBeforeUserFormSave',array(
	'mode' => 'new',
	'id' => $_POST['id'],
));


/* update user */
if ($user->save() == false) {
    return $modx->error->failure($modx->lexicon('user_err_save'));
}


$user->profile = $modx->newObject('modUserProfile');
$user->profile->fromArray($_POST);
$user->profile->set('internalKey',$user->get('id'));
$user->profile->set('blocked', isset($_POST['blocked']) && $_POST['blocked'] ? true : false);

if ($user->profile->save() == false) {
	return $modx->error->failure($modx->lexicon('user_err_save_attributes'));
}

/* invoke OnManagerSaveUser event */
$modx->invokeEvent('OnManagerSaveUser',array(
	'mode' => 'new',
	'userid' => $_POST['id'],
	'username' => $_POST['newusername'],
	'userpassword' => $_POST['newpassword'],
	'useremail' => $_POST['email'],
	'userfullname' => $_POST['fullname'],
	'userroleid' => $_POST['role'],
	'oldusername' => (($_POST['oldusername'] != $_POST['newusername']) ? $_POST['oldusername'] : ''),
	'olduseremail' => (($_POST['oldemail'] != $_POST['email']) ? $_POST['oldemail'] : '')
));

/* invoke OnUserFormSave event */
$modx->invokeEvent('OnUserFormSave',array(
	'mode' => 'new',
	'id' => $user->get('id'),
));

/*
 * manage user group memberships
 * :TODO: add modUserGroupRole and sub-group handling
 */
if (isset($_POST['user_groups']) && count($_POST['user_groups']) > 0) {
    foreach ($_POST['user_groups'] as $group_id) {
        $ug = $modx->newObject('modUserGroupMember');
        $ug->set('user_group',$group_id);
        $ug->set('member',$user->get('id'));
        $ug->save();
    }
}

/* converts date format dd-mm-yyyy to php date */
function convertDate($date) {
	if ($date == '')
		return false;
	list ($d, $m, $Y, $H, $M, $S) = sscanf($date, "%2d-%2d-%4d %2d:%2d:%2d");
	if (!$H && !$M && !$S)
		return strtotime("$m/$d/$Y");
	else
		return strtotime("$m/$d/$Y $H:$M:$S");
}

/* Send an email to the user */
function sendMailMessage($email, $uid, $pwd, $ufn) {
	global $modx;

	$message = $modx->config['signupemail_message'];
	// replace placeholders
	$message = str_replace("[[+uid]]", $uid, $message);
	$message = str_replace("[[+pwd]]", $pwd, $message);
	$message = str_replace("[[+ufn]]", $ufn, $message);
	$message = str_replace("[[+sname]]",$modx->config['site_name'], $message);
	$message = str_replace("[[+saddr]]", $modx->config['emailsender'], $message);
	$message = str_replace("[[+semail]]", $modx->config['emailsender'], $message);
	$message = str_replace("[[+surl]]", $modx->config['url_scheme'] . $modx->config['http_host'] . $modx->config['manager_url'], $message);

    $modx->getService('mail', 'mail.modPHPMailer');
    $modx->mail->set(MODX_MAIL_BODY, $message);
    $modx->mail->set(MODX_MAIL_FROM, $modx->config['emailsender']);
    $modx->mail->set(MODX_MAIL_FROM_NAME, $modx->config['site_name']);
    $modx->mail->set(MODX_MAIL_SENDER, $modx->config['emailsender']);
    $modx->mail->set(MODX_MAIL_SUBJECT, $modx->config['emailsubject']);
    $modx->mail->address('to', $email, $ufn);
    $modx->mail->address('reply-to', $modx->config['emailsender']);
    if (!$modx->mail->send()) {
        return $modx->error->failure($modx->lexicon('error_sending_email_to').$email);
        exit;
    }
    $modx->mail->reset();
}

/* log manager action */
$modx->logManagerAction('user_create','modUser',$user->get('id'));

if ($_POST['passwordnotifymethod'] == 's') {
	return $modx->error->success($modx->lexicon('user_created_password_message').$newPassword);
} else {
	return $modx->error->success();
}