<?php
/**
 * @package modx
 * @subpackage processors.workspace.lexicon.focus
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('lexicon');


if (!isset($_POST['namespace'])) $modx->error->failure($modx->lexicon('namespace_err_ns'));
$namespace = $modx->getObject('modNamespace',$_POST['namespace']);
if ($namespace == null) $modx->error->failure($modx->lexicon('namespace_err_nf'));

if (!isset($_POST['focus'])) $modx->error->failure($modx->lexicon('focus_err_ns'));
$focus = $modx->getObject('modLexiconFocus',$_POST['focus']);
if ($focus == null) $modx->error->failure($modx->lexicon('focus_err_nf'));

if (!isset($_POST['language'])) $_POST['language'] = 'en';

$entries = $modx->getCollection('modLexiconEntry',array(
    'namespace' => $namespace->get('name'),
    'focus' => $focus->get('id'),
    'language' => $_POST['language'],
));

$o = "<?php\n";
foreach ($entries as $entry) {
    $value = str_replace("'","\'",$entry->get('value'));
    $o .= "\$_lang['".$entry->get('name')."'] = '".$value."';\n";
}

$fileName = $modx->config['core_path'].'export/lexicon/'.$namespace->get('name').'/'.$focus->get('name').'.inc.php';

$cacheManager = $modx->getCacheManager();
$s = $cacheManager->writeFile($fileName,$o);

$modx->lexicon->success();