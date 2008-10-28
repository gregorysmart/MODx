<?php
/**
 * @package modx
 * @subpackage processors.workspace.lexicon.focus
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('lexicon');

if (!$modx->hasPermission('lexicons')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['namespace'])) return $modx->error->failure($modx->lexicon('namespace_err_ns'));
$namespace = $modx->getObject('modNamespace',$_POST['namespace']);
if ($namespace == null) return $modx->error->failure($modx->lexicon('namespace_err_nf'));

if (!isset($_POST['topic'])) return $modx->error->failure($modx->lexicon('topic_err_ns'));
$topic = $modx->getObject('modLexiconTopic',$_POST['topic']);
if ($topic == null) return $modx->error->failure($modx->lexicon('topic_err_nf'));

if (!isset($_POST['language'])) $_POST['language'] = 'en';

$entries = $modx->getCollection('modLexiconEntry',array(
    'namespace' => $namespace->get('name'),
    'topic' => $topic->get('id'),
    'language' => $_POST['language'],
));

$o = "<?php\n";
foreach ($entries as $entry) {
    $value = str_replace("'","\'",$entry->get('value'));
    $o .= "\$_lang['".$entry->get('name')."'] = '".$value."';\n";
}

$fileName = $modx->config['core_path'].'export/lexicon/'.$namespace->get('name').'/'.$topic->get('name').'.inc.php';

$cacheManager = $modx->getCacheManager();
$s = $cacheManager->writeFile($fileName,$o);

$modx->lexicon->success();