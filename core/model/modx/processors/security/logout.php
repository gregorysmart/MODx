<?php
/**
 * Properly log out the user, running any events and flushing the session.
 *
 * @package modx
 * @subpackage processors.security
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('login');

if (!$user= $modx->getUser()) return $modx->error->failure($modx->lexicon('not_logged_in'));

/* invoke OnBeforeManagerLogout event */
$modx->invokeEvent('OnBeforeManagerLogout',array(
    'userid' => $user->get('id'),
    'username' => $user->get('username'),
));

$modx->user->endSession();

/* invoke OnManagerLogout event */
$modx->invokeEvent('OnManagerLogout',array(
	'userid' => $internalKey,
	'username' => $username,
));

/* show login screen */
return $modx->error->success();