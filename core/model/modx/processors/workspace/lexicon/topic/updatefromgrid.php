<?php
/**
 * @package modx
 * @subpackage processors.workspace.lexicon.topic
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('lexicon');

if (!$modx->hasPermission('lexicons')) $modx->error->failure($modx->lexicon('permission_denied'));

$_DATA = $modx->fromJSON($_POST['data']);

if (!isset($_DATA['id'])) $modx->error->failure($modx->lexicon('topic_err_ns'));
$topic = $modx->newObject('modLexiconTopic',$_DATA['id']);
if ($topic == null) $modx->error->failure($modx->lexicon('topic_err_nf'));

if (!isset($_DATA['namespace'])) $modx->error->failure($modx->lexicon('namespace_err_ns'));
$namespace = $modx->newObject('modNamespace',$_DATA['namespace']);
if ($namespace == null) $modx->error->failure($modx->lexicon('namespace_err_nf'));

$topic->set('namespace',$namespace->get('name'));

if ($topic->save() === false) {
    $modx->error->failure($modx->lexicon('topic_err_save'));
}

$modx->error->success();