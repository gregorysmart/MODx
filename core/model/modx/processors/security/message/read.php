<?php
/**
 * @package modx
 * @subpackage processors.security.message
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('messages');
if (!$modx->hasPermission('messages')) $modx->error->failure($modx->lexicon('permission_denied'));

$message = $modx->getObject('modUserMessage',$_POST['id']);
if ($message == null) $error->failure($modx->lexicon('message_err_not_found'));

$message->set('messageread',true);
if (!$message->save()) $error->failure($modx->lexicon('message_err_save'));

$error->success();