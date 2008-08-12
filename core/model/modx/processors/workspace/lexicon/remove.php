<?php
/**
 * @package modx
 * @subpackage processors.workspace.lexicon
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('lexicon');

if (!isset($_POST['id'])) $modx->error->failure($modx->lexicon('entry_err_ns'));
$entry = $modx->getObject('modLexiconEntry',$_POST['id']);
if ($entry == null) {
    $modx->error->failure(sprintf($modx->lexicon('entry_err_nfs'),$_POST['id']));
}

if ($entry->remove() === false) {
    $modx->error->failure($modx->lexicon('entry_err_save'));
}

$r = $modx->lexicon->clearCache($entry->namespace.'/'.$entry->focus.'.cache.php');

$modx->error->success();