<?php
/**
 * @package modx
 * @subpackage processors.security.user
 */
/********************************************************/
/* BEGIN VALIDATION */

// new username
if (isset($_POST['newusername']) && $_POST['newusername'] != '') {
	$user_name = $modx->getObject('modUser',array('username' => $_POST['newusername']));
	if ($user_name != NULL) {
		if ($user_name->id != $_POST['id']) {
			$error->addField('new_user_name',$modx->lexicon('user_err_already_exists'));
		}
	}
	$user->set('username',$_POST['newusername']);
}

// password
if (isset($_POST['newpassword'])) {
	if (!isset($_POST['passwordnotifymethod'])) {
		$error->addField('password_notify_method',$modx->lexicon('user_err_not_specified_notification_method'));
	}
	if ($_POST['passwordgenmethod'] == 'g') {
		$autoPassword = generate_password(8);
		$user->set('password', $user->encode($autoPassword));
		$newPassword= $autoPassword;
	} else {
		if ($_POST['specifiedpassword'] == '') {
			$error->addField('password',$modx->lexicon('user_err_not_specified_password'));
		} elseif ($_POST['specifiedpassword'] != $_POST['confirmpassword']) {
			$error->addField('password',$modx->lexicon('user_err_password_no_match'));
		} elseif (strlen($_POST['specifiedpassword']) < 6) {
			$error->addField('password',$modx->lexicon('user_err_password_too_short'));
		} else {
			$user->set('password',$user->encode($_POST['specifiedpassword']));
			$newPassword= $_POST['specifiedpassword'];
		}
	}
}

// email
if (!isset($_POST['email']) || $_POST['email'] == '')
	$error->addField('email',$modx->lexicon('user_err_not_specified_email'));

// check if the email address already exists
$user_email = $modx->getObject('modUserProfile',array('email' => $_POST['email']));
if ($user_email != NULL) {
	if ($user_email->internalKey != $_POST['id'])
		$error->addField('email',$modx->lexicon('user_err_already_exists_email'));
}

// phone number
if (isset($_POST['phone']) && $_POST['phone'] != '') {
	$_POST['phone'] = str_replace(' ','',$_POST['phone']);
	$_POST['phone'] = str_replace('-','',$_POST['phone']);
	$_POST['phone'] = str_replace('(','',$_POST['phone']);
	$_POST['phone'] = str_replace(')','',$_POST['phone']);
	$_POST['phone'] = str_replace('+','',$_POST['phone']);
	if ((strlen($_POST['phone']) < 10) || (strlen($_POST['phone']) > 11)) {
		//phone number is either too big or too small
		$error->addField('phone',$modx->lexicon('user_err_not_specified_phonenumber'));
	}
}

// mobilephone number
if (isset($_POST['mobilephone']) && $_POST['mobilephone'] != '') {
	$_POST['mobilephone'] = str_replace(' ','',$_POST['mobilephone']);
	$_POST['mobilephone'] = str_replace('-','',$_POST['mobilephone']);
	$_POST['mobilephone'] = str_replace('(','',$_POST['mobilephone']);
	$_POST['mobilephone'] = str_replace(')','',$_POST['mobilephone']);
	$_POST['mobilephone'] = str_replace('+','',$_POST['mobilephone']);
	if ((strlen($_POST['mobilephone']) < 10) || (strlen($_POST['mobilephone']) > 11)) {
		//phone number is either too big or too small
		$error->addField('mobilephone',$modx->lexicon('user_err_not_specified_mobnumber'));
	}
}

// birthdate
if (isset($_POST['dob']) && $_POST['dob'] != '') {
	$_POST['dob'] = str_replace('-','/',$_POST['dob']);
	if (!$_POST['dob'] = strtotime($_POST['dob']))
		$error->addField('dob',$modx->lexicon('user_err_not_specified_dob'));
}


// blocked until
if (isset($_POST['blockeduntil']) && $_POST['blockeduntil'] != '') {
	$_POST['blockeduntil'] = str_replace('-','/',$_POST['blockeduntil']);
	if (!$_POST['blockeduntil'] = strtotime($_POST['blockeduntil']))
		$error->addField('blockeduntil',$modx->lexicon('user_err_not_specified_blockeduntil'));
}

// blocked after
if (isset($_POST['blockedafter']) && $_POST['blockedafter'] != '') {
	$_POST['blockedafter'] = str_replace('-','/',$_POST['blockedafter']);
	if (!$_POST['blockedafter'] = strtotime($_POST['blockedafter']))
		$error->addField('blockedafter',$modx->lexicon('user_err_not_specified_blockedafter'));
}


// get fields for better error displaying
$fs = $error->getFields();
$fields = '<ul>';
foreach ($fs as $f) {
	$fields .= '<li>'.ucwords(str_replace('_',' ',$f)).'</li>';
}
$fields .= '</ul>';

if ($error->hasError()) {
	$error->failure(sprintf($modx->lexicon('check_fields_error').$fields));
}

/* END VALIDATION */
/********************************************************/

// Generate password
function generate_password($length = 10) {
	$allowable_characters = 'abcdefghjkmnpqrstuvxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';
	$ps_len = strlen($allowable_characters);
	mt_srand((double) microtime() * 1000000);
	$pass = '';
	for ($i = 0; $i < $length; $i++) {
		$pass .= $allowable_characters[mt_rand(0, $ps_len -1)];
	}
	return $pass;
}