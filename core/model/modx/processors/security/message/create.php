<?php
/**
 * @package modx
 * @subpackage processors.security.message
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('messages','user');

// validation
if (!isset($_POST['subject']) || $_POST['subject'] == '') {
	$modx->error->failure($modx->lexicon('message_err_not_specified_subject'));
}

// process message
switch ($_POST['type']) {
	case 'user':
		$user = $modx->getObject('modUser',$_POST['user']);
		if ($user == null) {
		    $modx->error->failure($modx->lexicon('user_err_not_found'));
        }

		$message = $modx->newObject('modUserMessage');
		$message->set('subject',$_POST['subject']);
		$message->set('message',$_POST['message']);
		$message->set('sender',$modx->getLoginUserID());
		$message->set('recipient',$user->id);
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
            'role' => $role->id,
        ));

		foreach ($users as $user) {
			if ($user->internalKey != $modx->getLoginUserID()) {
				$message = $modx->newObject('modUserMessage');
				$message->set('recipient',$user->internalKey);
				$message->set('subject',$_POST['subject']);
				$message->set('message',$_POST['message']);
				$message->set('sender',$modx->getLoginUserID());
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
            'user_group' => $group->id,
        ));

        foreach ($users as $user) {
            if ($user->internalKey != $modx->getLoginUserID()) {
                $message = $modx->newObject('modUserMessage');
                $message->set('recipient',$user->internalKey);
                $message->set('subject',$_POST['subject']);
                $message->set('message',$_POST['message']);
                $message->set('sender',$modx->getLoginUserID());
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
			if ($user->id != $user_id) {
				$message = $modx->newObject('modUserMessage');
				$message->set('recipient',$user->id);
				$message->set('sender',$modx->getLoginUserID());
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