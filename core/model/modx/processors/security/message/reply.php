<?php
/**
 * @package modx
 * @subpackage processors.security.message
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('messages','user');

//$old_message = $modx->getObject('modUserMessage',$_POST['id']);
//if ($old_message == NULL) $error->failure($modx->lexicon('message_err_not_found'));

// validation
if (!isset($_POST['subject']) || $_POST['subject'] == '') {
	$error->addField('m_reply_subject',$modx->lexicon('message_err_not_specified_subject'));
}

$fs = $error->getFields();
$fields = '<ul>';
foreach ($fs as $f)
	$fields .= '<li>'.ucwords(str_replace('_',' ',$f)).'</li>';
$fields .= '</ul>';

if ($error->hasError()) $error->failure($modx->lexicon('validation_system_settings').$fields);

// process message
switch ($_POST['type']) {
	case 'user':
		$user = $modx->getObject('modUser',$_POST['user']);
		if ($user == NULL) $error->failure($modx->lexicon('user_err_not_found'));

		$message = $modx->newObject('modUserMessage');
		$message->set('type','Message');
		$message->set('subject',$_POST['subject']);
		$message->set('message',$_POST['message']);
		$message->set('sender',$modx->getLoginUserID());
		$message->set('recipient',$user->id);
		$message->set('private',true);
		$message->set('postdate',time());
		$message->set('messageread',false);

		if (!$message->save()) $error->failure($modx->lexicon('message_err_save'));
		break;

	case 'role':
		$role = $modx->getObject('modUserRole',$_POST['role']);
		if ($role == NULL) $error->failure($modx->lexicon('role_err_not_found'));

		$users = $modx->getCollection('modUserProfile',array('role' => $_POST['role']));

		foreach ($users as $user) {
			//if ($user->internalKey != $modx->getLoginUserID()) {
				$message = $modx->newObject('modUserMessage');
				$message->set('recipient',$user->internalKey);
				$message->set('subject',$_POST['subject']);
				$message->set('message',$_POST['message']);
				$message->set('sender',$modx->getLoginUserID());
				$message->set('postdate',time());
				$message->set('type','Message');
				$message->set('private',false);
				if (!$message->save()) $error->failure($modx->lexicon('message_err_save'));
			//}
		}
		break;
	case 'all':
		$users = $modx->getCollection('modUser');
		foreach ($users as $user) {
			if ($user->id != $user_id) {
				$message = $modx->newObject('modUserMessage');
				$message->set('recipient',$user->id);
				$message->set('sender',$modx->getLoginUserID());
				$message->set('subject',$_POST['subject']);
				$message->set('message',$_POST['message']);
				$message->set('postdate',time());
				$message->set('type','Message');
				$message->set('private',false);
				if (!$message->save()) $error->failure($modx->lexicon('message_err_save'));
			}
		}

		break;
}

$error->success();