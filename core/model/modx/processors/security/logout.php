<?php
/**
 * @package modx
 * @subpackage processors.security
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('login');

if (!$user= $modx->getUser()) $error->failure($modx->lexicon('not_logged_in'));

// invoke OnBeforeManagerLogout event
$modx->invokeEvent('OnBeforeManagerLogout',array(
    'userid' => $user->id,
    'username' => $user->username,
));

$modx->user->endSession();

// invoke OnManagerLogout event
$modx->invokeEvent('OnManagerLogout',array(
	'userid' => $internalKey,
	'username' => $username
));

// show login screen
$error->success();