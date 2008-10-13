<?php
/**
 * @package modx
 * @subpackage processors.system.action
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('action','menu','context');

if (!$modx->hasPermission('actions')) $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['id'])) $modx->error->failure($modx->lexicon('action_err_ns'));
$action = $modx->getObject('modAction',$_REQUEST['id']);
if ($action == null) $modx->error->failure($modx->lexicon('action_err_nf'));

if (!isset($_POST['controller']) || $_POST['controller'] == '') {
	$modx->error->failure($modx->lexicon('controller_err_ns'));
}

$loadheaders = isset($_POST['loadheaders']) ? true : false;

if (!isset($_POST['parent'])) $modx->error->failure($modx->lexicon('action_parent_err_ns'));
if ($_POST['parent'] == 0) {
	$parent = $modx->newObject('modAction');
	$parent->set('id',0);
} else {
	$parent = $modx->getObject('modAction',$_POST['parent']);
	if ($parent == null) $modx->error->failure($modx->lexicon('action_parent_err_nf'));
}

if (!isset($_POST['context_key']) || $_POST['context_key'] == '') $modx->error->failure($modx->lexicon('context_err_nf'));
$context = $modx->getObject('modContext',$_POST['context_key']);
if ($context == null) $modx->error->failure($modx->lexicon('context_err_nf'));


$action->set('context_key',$context->get('key'));
$action->set('parent',$parent->get('id'));
$action->set('controller',$_POST['controller']);
$action->set('loadheaders',$loadheaders);
$action->set('lang_topics',$_POST['lang_topics']);
$action->set('assets',$_POST['assets']);

if (!$action->save()) $modx->error->failure($modx->lexicon('action_err_save'));

/* log manager action */
$modx->logManagerAction('action_update','modAction',$action->get('id'));

$modx->error->success();