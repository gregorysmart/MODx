<?php
/**
 * Properly log out the user, running any events and flushing the session.
 *
 * @package modx
 * @subpackage processors.security
 */
$modx->lexicon->load('login');

if (!$modx->user->isAuthenticated()) return $modx->error->failure($modx->lexicon('not_logged_in'));

$loginContext= isset ($_POST['login_context']) ? $_POST['login_context'] : $modx->context->get('key');

if ($loginContext == 'mgr') {
    /* invoke OnBeforeManagerLogout event */
    $modx->invokeEvent('OnBeforeManagerLogout',array(
        'userid' => $modx->user->get('id'),
        'username' => $modx->user->get('username'),
    ));
} else {
    $modx->invokeEvent('OnBeforeWebLogout',array(
        'userid' => $modx->user->get('id'),
        'username' => $modx->user->get('username'),
    ));
}

$modx->user->removeSessionContext($loginContext);

if ($loginContext == 'mgr') {
    /* invoke OnManagerLogout event */
    $modx->invokeEvent('OnManagerLogout',array(
        'userid' => $modx->user->get('id'),
        'username' => $modx->user->get('username'),
    ));
} else {
    $modx->invokeEvent('OnWebLogout',array(
        'userid' => $modx->user->get('id'),
        'username' => $modx->user->get('username'),
    ));
}

return $modx->error->success();