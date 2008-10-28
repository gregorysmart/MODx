<?php
/**
 * @package modx
 * @subpackage processors.security.message
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('messages','user');

if (!$modx->hasPermission('messages')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* validation */
if (!isset($_POST['subject']) || $_POST['subject'] == '') {
	$modx->error->addField('m_reply_subject',$modx->lexicon('message_err_not_specified_subject'));
}

$fs = $modx->error->getFields();
$fields = '<ul>';
foreach ($fs as $f)
	$fields .= '<li>'.ucwords(str_replace('_',' ',$f)).'</li>';
$fields .= '</ul>';

if ($modx->error->hasError()) return $modx->error->failure($modx->lexicon('validation_system_settings').$fields);

/* process message */
switch ($_POST['type']) {
	case 'user':
		$user = $modx->getObject('modUser',$_POST['user']);
		if ($user == null) return $modx->error->failure($modx->lexicon('user_err_not_found'));

		$message = $modx->newObject('modUserMessage');
		$message->set('type','Message');
		$message->set('subject',$_POST['subject']);
		$message->set('message',$_POST['message']);
		$message->set('sender',$modx->user->get('id'));
		$message->set('recipient',$user->get('id'));
		$message->set('private',true);
		$message->set('postdate',time());
		$message->set('messageread',false);

		if (!$message->save()) return $modx->error->failure($modx->lexicon('message_err_save'));
		break;

	case 'role':
		$role = $modx->getObject('modUserRole',$_POST['role']);
		if ($role == null) return $modx->error->failure($modx->lexicon('role_err_not_found'));

		$users = $modx->getCollection('modUserProfile',array('role' => $_POST['role']));

		foreach ($users as $user) {
			if ($user->get('internalKey') != $modx->user->get('id')) {
				$message = $modx->newObject('modUserMessage');
				$message->set('recipient',$user->get('internalKey'));
				$message->set('subject',$_POST['subject']);
				$message->set('message',$_POST['message']);
				$message->set('sender',$modx->user->get('id'));
				$message->set('postdate',time());
				$message->set('type','Message');
				$message->set('private',false);
				if (!$message->save()) return $modx->error->failure($modx->lexicon('message_err_save'));
			}
		}
		break;
	case 'all':
		$users = $modx->getCollection('modUser');
		foreach ($users as $user) {
			if ($user->get('id') != $modx->user->get('id')) {
				$message = $modx->newObject('modUserMessage');
				$message->set('recipient',$user->get('id'));
				$message->set('sender',$modx->user->get('id'));
				$message->set('subject',$_POST['subject']);
				$message->set('message',$_POST['message']);
				$message->set('postdate',time());
				$message->set('type','Message');
				$message->set('private',false);
				if (!$message->save()) return $modx->error->failure($modx->lexicon('message_err_save'));
			}
		}
		break;
}
return $modx->error->success();