<?php
/**
 * @package modx
 * @subpackage processors.security.message
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('messages','user');

if (!$modx->hasPermission('messages')) $modx->error->failure($modx->lexicon('permission_denied'));

/* validation */
if (!isset($_POST['subject']) || $_POST['subject'] == '') {
	$modx->error->failure($modx->lexicon('message_err_not_specified_subject'));
}

/* process message */
switch ($_POST['type']) {
	case 'user':
		$user = $modx->getObject('modUser',$_POST['user']);
		if ($user == null) {
		    $modx->error->failure($modx->lexicon('user_err_not_found'));
        }

		$message = $modx->newObject('modUserMessage');
		$message->set('subject',$_POST['subject']);
		$message->set('message',$_POST['message']);
		$message->set('sender',$modx->user->get('id'));
		$message->set('recipient',$user->get('id'));
		$message->set('private',true);
		$message->set('date_sent',time());
		$message->set('read',false);

		if ($message->save() === false) {
		    $modx->error->failure($modx->lexicon('message_err_save'));
        }
		break;

	case 'role':
		$role = $modx->getObject('modUserGroupRole',$_POST['role']);
		if ($role == null) {
		    $modx->error->failure($modx->lexicon('role_err_not_found'));
        }

		$users = $modx->getCollection('modUserGroupMember',array(
            'role' => $role->get('id'),
        ));

		foreach ($users as $user) {
			if ($user->get('internalKey') != $modx->user->get('id')) {
				$message = $modx->newObject('modUserMessage');
				$message->set('recipient',$user->get('internalKey'));
				$message->set('subject',$_POST['subject']);
				$message->set('message',$_POST['message']);
				$message->set('sender',$modx->user->get('id'));
				$message->set('date_sent',time());
				$message->set('private',false);
				if ($message->save() === false) {
				    $modx->error->failure($modx->lexicon('message_err_save'));
                }
			}
		}
		break;
    case 'usergroup':
        $group = $modx->getObject('modUserGroup',$_POST['group']);
        if ($group == null) {
            $modx->error->failure($modx->lexicon('group_err_not_found'));
        }

        $users = $modx->getCollection('modUserGroupMember',array(
            'user_group' => $group->get('id'),
        ));

        foreach ($users as $user) {
            if ($user->get('internalKey') != $modx->user->get('id')) {
                $message = $modx->newObject('modUserMessage');
                $message->set('recipient',$user->get('internalKey'));
                $message->set('subject',$_POST['subject']);
                $message->set('message',$_POST['message']);
                $message->set('sender',$modx->user->get('id'));
                $message->set('date_sent',time());
                $message->set('private',false);
                if ($message->save() === false) {
                    $modx->error->failure($modx->lexicon('message_err_save'));
                }
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
				$message->set('date_sent',time());
				$message->set('private',false);
				if ($message->save() === false) {
				    $modx->error->failure($modx->lexicon('message_err_save'));
                }
			}
		}
		break;
}

$modx->error->success('',$message);