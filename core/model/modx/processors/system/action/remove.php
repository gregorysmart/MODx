<?php
/**
 * @package modx
 * @subpackage processors.system.action
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('action','menu');

if (!$modx->hasPermission('actions')) $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['id'])) $error->failure($modx->lexicon('action_err_ns'));
$action = $modx->getObject('modAction',$_REQUEST['id']);
if ($action == null) $error->failure($modx->lexicon('action_err_nf'));

if (!$action->remove()) $error->failure($modx->lexicon('action_err_remove'));

// log manager action
$modx->logManagerAction('action_delete','modAction',$action->id);

$error->success();