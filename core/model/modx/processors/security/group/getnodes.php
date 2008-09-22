<?php
/**
 * @package modx
 * @subpackage processors.security.group
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('user');
if (!$modx->hasPermission('access_permissions')) $modx->error->failure($modx->lexicon('permission_denied'));

$_REQUEST['id'] = !isset($_REQUEST['id']) ? 0 : str_replace('n_ug_','',$_REQUEST['id']);

$g = $modx->getObject('modUserGroup',$_REQUEST['id']);
$groups = $modx->getCollection('modUserGroup',array('parent' => $_REQUEST['id']));

$da = array();
foreach ($groups as $group) {
	$da[] = array(
		'text' => $group->name,
		'id' => 'n_ug_'.$group->id,
		'leaf' => 0,
		'type' => 'usergroup',
		'cls' => 'folder',
        'menu' => array(
            array(
                'text' => $modx->lexicon('add_user_to_group'),
                'handler' => 'this.addUser',
            ),
            '-',
            array(
                'text' => $modx->lexicon('create_user_group'),
                'handler' => 'this.create',
            ),
            array(
                'text' => $modx->lexicon('user_group_update'),
                'handler' => 'this.update',
            ),
            '-',
            array(
                'text' => $modx->lexicon('delete_user_group'),
                'handler' => 'this.remove',
            ),
        ),
	);
}
if ($g != null) {
	$users = $g->getUsersIn();
	foreach ($users as $user) {
		$da[] = array(
			'text' => $user->username,
			'id' => 'n_user_'.$user->id,
			'leaf' => 1,
			'type' => 'user',
			'cls' => '',
            'menu' => array(
                array(
                    'text' => $modx->lexicon('remove_user_from_group'),
                    'handler' => 'this.removeUser',
                ),
            ),
		);
	}
}

echo $modx->toJSON($da);