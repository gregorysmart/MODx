<?php
/**
 * @package modx
 * @subpackage processors.system.action
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('action','menu','context');

if (!isset($_POST['id'])) $error->failure($modx->lexicon('action_err_ns'));
$action = $modx->getObject('modAction',$_REQUEST['id']);
if ($action == null) $error->failure($modx->lexicon('action_err_nf'));

if (!isset($_POST['controller']) || $_POST['controller'] == '') {
	$error->failure($modx->lexicon('controller_err_ns'));
}

$loadheaders = isset($_POST['loadheaders']) ? true : false;

if (!isset($_POST['parent'])) $error->failure($modx->lexicon('action_parent_err_ns'));
if ($_POST['parent'] == 0) {
	$parent = $modx->newObject('modAction');
	$parent->id = 0;
} else {
	$parent = $modx->getObject('modAction',$_POST['parent']);
	if ($parent == null) $error->failure($modx->lexicon('action_parent_err_nf'));
}

if (!isset($_POST['context_key']) || $_POST['context_key'] == '') $error->failure($modx->lexicon('context_err_nf'));
$context = $modx->getObject('modContext',$_POST['context_key']);
if ($context == null) $error->failure($modx->lexicon('context_err_nf'));


$action->set('context_key',$context->key);
$action->set('parent',$parent->id);
$action->set('controller',$_POST['controller']);
$action->set('loadheaders',$loadheaders);
$action->set('lang_foci',$_POST['lang_foci']);
$action->set('assets',$_POST['assets']);

if (!$action->save()) $error->failure($modx->lexicon('action_err_save'));

// log manager action
$modx->logManagerAction('action_update','modAction',$action->id);

$error->success();