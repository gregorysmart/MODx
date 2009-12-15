<?php
/**
 * Get a user
 *
 * @param integer $id The ID of the user
 *
 * @package modx
 * @subpackage processors.security.user
 */
if (!$modx->hasPermission(array('access_permissions' => true, 'edit_user' => true))) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}
$modx->lexicon->load('user');

/* get user */
if (empty($_REQUEST['id'])) return $modx->error->failure($modx->lexicon('user_err_ns'));
$user = $modx->getObject('modUser',$_REQUEST['id']);
if (!$user) return $modx->error->failure($modx->lexicon('user_err_not_found'));

$user->profile = $user->getOne('Profile');

/* if set, get groups for user */
if (!empty($_REQUEST['getGroups'])) {
    $c = $modx->newQuery('modUserGroupMember');
    $c->select('
        modUserGroupMember.*,
        UserGroupRole.name AS role_name,
        UserGroup.name AS user_group_name
    ');
    $c->leftJoin('modUserGroupRole','UserGroupRole');
    $c->innerJoin('modUserGroup','UserGroup');
    $c->where(array(
        'member' => $user->get('id'),
    ));
    $members = $modx->getCollection('modUserGroupMember',$c);

    $data = array();
    foreach ($members as $member) {
        $roleName = $member->get('role_name');
        if ($member->get('role') == 0) { $roleName = $modx->lexicon('none'); }
        $data[] = array(
            $member->get('user_group'),
            $member->get('user_group_name'),
            $member->get('member'),
            $member->get('role'),
            empty($roleName) ? '' : $roleName,
        );
    }
    $user->set('groups','(' . $modx->toJSON($data) . ')');
}

$userArray = $user->toArray();
$userArray = array_merge($user->profile->toArray(),$userArray);
$userArray['dob'] = !empty($userArray['dob']) ? strftime('%m/%d/%Y',$userArray['dob']) : '';
$userArray['blockeduntil'] = !empty($userArray['blockeduntil']) ? strftime('%m/%d/%Y %I:%M %p',$userArray['blockeduntil']) : '';
$userArray['blockedafter'] = !empty($userArray['blockedafter']) ? strftime('%m/%d/%Y %I:%M %p',$userArray['blockedafter']) : '';
$userArray['lastlogin'] = !empty($userArray['lastlogin']) ? strftime('%m/%d/%Y',$userArray['lastlogin']) : '';


return $modx->error->success('',$userArray);
