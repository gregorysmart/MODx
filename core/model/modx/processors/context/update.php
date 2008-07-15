<?php
/**
 * @package modx
 * @subpackage processors.context
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('context');

if (!$modx->hasPermission('edit_context')) $error->failure($modx->lexicon('permission_denied'));

$context= $modx->getObject('modContext', $_REQUEST['key']);
if ($context == null) $error->failure($modx->lexicon('context_err_nf'));

$context->fromArray($_POST);

if (!$context->save()) $error->failure($modx->lexicon('context_err_save'));

// log manager action
$modx->logManagerAction('context_update','modContext',$context->id);

$error->success('', $context);