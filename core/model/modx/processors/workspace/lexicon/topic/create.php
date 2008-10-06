<?php
/**
 * @package modx
 * @subpackage processors.workspace.lexicon.topic
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('lexicon');

if (!$modx->hasPermission('lexicons')) $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['namespace'])) $modx->error->failure($modx->lexicon('namespace_err_ns'));
$namespace = $modx->getObject('modNamespace',$_POST['namespace']);
if ($namespace == null) $modx->error->failure($modx->lexicon('namespace_err_nf'));

$topic = $modx->newObject('modLexiconTopic');
$topic->set('name',$_POST['name']);
$topic->set('namespace',$namespace->name);

if ($topic->save() === false) {
	$modx->error->failure($modx->lexicon('topic_err_create'));
}

$modx->error->success();