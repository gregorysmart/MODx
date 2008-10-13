<?php
/**
 * @package modx
 * @subpackage processors.security.profile
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('user');

if (!$modx->hasPermission('change_password')) $modx->error->failure($modx->lexicon('permission_denied'));

if (isset($_POST['password_reset'])) {
    if (md5($_POST['password_old']) != $modx->user->get('password'))
        $modx->error->failure($modx->lexicon('user_err_password_invalid_old'));

    if ($_POST['password_new'] != $_POST['password_confirm'])
        $modx->error->failure($modx->lexicon('user_err_passwords_no_match'));

    if (strlen($_POST['password_new']) < 6)
        $modx->error->failure($modx->lexicon('user_err_password_too_short'));

    $modx->user->set('password',md5($_POST['password_new']));
    $modx->user->save();
}


/* log manager action */
$modx->logManagerAction('change_profile_password','modUser',$modx->user->get('id'));

$modx->error->success($modx->lexicon('success'));