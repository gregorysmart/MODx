<?php
/**
 * @package modx
 * @subpackage processors.security.profile
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('user');

if (!$modx->hasPermission('change_profile')) $modx->error->failure($modx->lexicon('permission_denied'));

$profile = $modx->user->getOne('modUserProfile');
if ($profile == null) $modx->error->failure($modx->lexicon('user_profile_err_not_found'));

$_POST['dob'] = strtotime($_POST['dob']);
$profile->fromArray($_POST);

if ($profile->save() == false) {
    $modx->error->failure($modx->lexicon('user_profile_err_save'));
}

/* log manager action */
$modx->logManagerAction('save_profile','modUser',$modx->user->get('id'));

$modx->error->success($modx->lexicon('success'));