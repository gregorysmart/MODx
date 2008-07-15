<?php
/**
 * @package modx
 * @subpackage processors.security.message
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('messages','user');

// get message
$message = $modx->getObject('modUserMessage',$_POST['id']);
if ($message == null) {
    $modx->error->failure($modx->lexicon('message_err_not_found'));
}


// make sure user is message recipient
if ($message->recipient != $modx->getLoginUserID()) {
	$modx->error->failure($modx->lexicon('message_err_remove_notauth'));
}

// delete message
if ($message->remove() === false) {
	$modx->error->failure($modx->lexicon('message_err_remove'));
}

$modx->error->success();