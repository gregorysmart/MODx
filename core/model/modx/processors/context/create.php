<?php
/**
 * @package modx
 * @subpackage processors.context
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('context');

if (!$modx->hasPermission('new_context')) $error->failure($modx->lexicon('permission_denied'));

$context= $modx->newObject('modContext');
$context->fromArray($_POST, '', true);

if (!$context->save()) $error->failure($modx->lexicon('context_err_create'));

// log manager action
$modx->logManagerAction('context_create','modContext',$context->id);

$error->success('', $context);