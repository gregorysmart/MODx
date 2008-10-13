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
$topic = $modx->getObject('modLexiconTopic',array(
    'name' => $_POST['focus'],
    'namespace' => $namespace->get('name'),
));
if ($topic == null) {
	$topic = $modx->newObject('modLexiconTopic');
    $topic->set('name',$_POST['topic']);
    $topic->set('namespace',$namespace->get('name'));
    $topic->save();
}
if (!isset($_FILES['lexicon'])) $modx->error->failure($modx->lexicon('lexicon_import_err_ns'));
$_FILE = $_FILES['lexicon'];

if ($_FILE['error'] != 0) $modx->error->failure($modx->lexicon('lexicon_import_err_upload'));

$_lang = array();
@include_once $_FILE['tmp_name'];

foreach ($_lang as $key => $str) {
	$entry = $modx->getObject('modLexiconEntry',array(
        'name' => $key,
        'topic' => $topic->get('id'),
        'namespace' => $namespace->get('name'),
        'language' => $_POST['language'],
    ));
    if ($entry == null) {
    	$entry = $modx->newObject('modLexiconEntry');
        $entry->set('name',$key);
        $entry->set('topic',$topic->get('id'));
        $entry->set('namespace',$focus->get('namespace'));
        $entry->set('language',$_POST['language']);
    }
    $entry->set('value',$str);

    $entry->save();
}


$modx->lexicon->success();