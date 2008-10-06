<?php
/**
 * @package modx
 * @subpackage processors.workspace.lexicon.focus
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('lexicon');

if (!$modx->hasPermission('lexicons')) $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['namespace'])) $modx->error->failure($modx->lexicon('namespace_err_ns'));
$namespace = $modx->getObject('modNamespace',$_POST['namespace']);
if ($namespace == null) $modx->error->failure($modx->lexicon('namespace_err_nf'));

if (!isset($_POST['topic'])) $modx->error->failure($modx->lexicon('topic_err_ns'));
$topic = $modx->getObject('modLexiconTopic',$_POST['topic']);
if ($topic == null) $modx->error->failure($modx->lexicon('topic_err_nf'));

$entry = $modx->newObject('modLexiconEntry');
$entry->set('name',$_POST['name']);
$entry->set('namespace',$namespace->get('name'));
$entry->set('topic',$topic->get('id'));
$entry->set('language',$_POST['language']);
$entry->set('value',$_POST['value']);
$entry->set('createdon',date('Y-m-d h:i:s'));

if ($entry->save() === false) {
    $modx->error->failure($modx->lexicon('entry_err_create'));
}

$entry->clearCache();

$modx->error->success();