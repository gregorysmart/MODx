<?php
/**
 * @package modx
 * @subpackage processors.security.user
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('user');

if (!$modx->hasPermission(array('access_permissions' => true, 'edit_user' => true))) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}

if (!isset($_POST['id'])) return $modx->error->failure($modx->lexicon('user_err_ns'));
$user = $modx->getObject('modUser',$_POST['id']);
if ($user == null) return $modx->error->failure($modx->lexicon('user_err_not_found'));

$user->profile = $user->getOne('modUserProfile');
$ua = $user->toArray();
$ua = array_merge($ua,$user->profile->toArray());
$ua['dob'] = $ua['dob'] != '0'
    ? strftime('%m/%d/%Y',$ua['dob'])
    : '';
$ua['blockeduntil'] = $ua['blockeduntil'] != '0'
    ? strftime('%m/%d/%Y %I:%M %p',$ua['blockeduntil'])
    : '';
$ua['blockedafter'] = $ua['blockedafter'] != '0'
    ? strftime('%m/%d/%Y %I:%M %p',$ua['blockedafter'])
    : '';

return $modx->error->success('',$ua);
