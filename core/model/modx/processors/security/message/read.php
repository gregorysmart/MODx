<?php
/**
 * @package modx
 * @subpackage processors.security.message
 */
$modx->lexicon->load('messages');

if (!$modx->hasPermission('messages')) return $modx->error->failure($modx->lexicon('permission_denied'));

$message = $modx->getObject('modUserMessage',$_POST['id']);
if ($message == null) return $modx->error->failure($modx->lexicon('message_err_not_found'));

$message->set('messageread',true);
if ($message->save() == false) {
    return $modx->error->failure($modx->lexicon('message_err_save'));
}

return $modx->error->success();