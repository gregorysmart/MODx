<?php
/**
 * @package modx
 * @subpackage processors.security.user
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('user');

if (!$modx->hasPermission('save_user')) $error->failure($modx->lexicon('permission_denied'));

$user = $modx->getObject('modUser',$_POST['id']);
if ($user == NULL) $error->failure($modx->lexicon('user_err_not_found'));

$newPassword= '';

//$result = $modx->loadProcessor('security/user/_validation.php',array('error' => $error));
require_once MODX_PROCESSORS_PATH.'security/user/_validation.php';

if ($_POST['passwordnotifymethod'] == 'e') {
	sendMailMessage($_POST['email'], $_POST['username'],$user->get('password'),$_POST['fullname']);
}


// invoke OnBeforeUserFormSave event
$modx->invokeEvent('OnBeforeUserFormSave',array(
	'mode' => 'upd',
	'id' => $_POST['id'],
));

// update user
if (!$user->save()) $error->failure($modx->lexicon('user_err_save'));

$user->profile = $user->getOne('modUserProfile');

$user->profile->set('fullname',$_POST['fullname']);
$user->profile->set('role',$_POST['role']);
$user->profile->set('email',$_POST['email']);
$user->profile->set('phone',$_POST['phone']);
$user->profile->set('mobilephone',$_POST['mobilephone']);
$user->profile->set('fax',$_POST['fax']);
$user->profile->set('zip',$_POST['zip']);
$user->profile->set('state',$_POST['state']);
$user->profile->set('country',$_POST['country']);
$user->profile->set('gender',$_POST['gender']);
$user->profile->set('dob',$_POST['dob']);
$user->profile->set('photo',$_POST['photo']);
$user->profile->set('comment',$_POST['comment']);
$user->profile->set('blocked',$_POST['blocked']);
$user->profile->set('blockeduntil',$_POST['blockeduntil']);
$user->profile->set('blockedafter',$_POST['blockedafter']);

if (!$user->profile->save()) {
	$error->failure($modx->lexicon('user_profile_err_save'));
}

// Save user settings
//$modx->loadProcessor('security/user/setting/updateAll.php',array('error' => $error));

// invoke OnManagerSaveUser event
$modx->invokeEvent('OnManagerSaveUser',array(
	'mode' => 'upd',
	'userid' => $_POST['id'],
	'username' => $_POST['newusername'],
	'userpassword' => $_POST['newpassword'],
	'useremail' => $_POST['email'],
	'userfullname' => $_POST['fullname'],
	'userroleid' => $_POST['role'],
	'oldusername' => (($_POST['oldusername'] != $_POST['newusername']) ? $_POST['oldusername'] : ''),
	'olduseremail' => (($_POST['oldemail'] != $_POST['email']) ? $_POST['oldemail'] : '')
));

// invoke OnUserFormSave event
$modx->invokeEvent('OnUserFormSave',array(
	'mode' => 'upd',
	'id' => $_POST['id'],
));

$ugs = $modx->getCollection('modUserGroupMember',array('member' => $user->id));
if (count($ugs) > 0) {
    foreach ($ugs as $ug)
        $ug->remove();
}

if (count($_POST['user_groups']) > 0) {
    foreach ($_POST['user_groups'] as $group_id) {
        $ug = $modx->newObject('modUserGroupMember');
        $ug->set('user_group',$group_id);
        $ug->set('member',$user->id);
        if (!$ug->save())
            $error->failure($modx->lexicon('user_group_member_err_create'));
    }
}

// converts date format dd-mm-yyyy to php date
function convertDate($date) {
	if ($date == '')
		return false;
	list ($d, $m, $Y, $H, $M, $S) = sscanf($date, "%2d-%2d-%4d %2d:%2d:%2d");
	if (!$H && !$M && !$S)
		return strtotime("$m/$d/$Y");
	else
		return strtotime("$m/$d/$Y $H:$M:$S");
}


// Send an email to the user
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
        die($modx->lexicon('error_sending_email_to').$email);
        exit;
    }
    $modx->mail->reset();
}

// log manager action
$modx->logManagerAction('user_update','modUser',$user->id);

if (isset($_POST['newpassword']) && $_POST['passwordnotifymethod'] == 's') {
	$error->success($modx->lexicon('user_created_password_message').$newPassword);
} else {
	$error->success();
}