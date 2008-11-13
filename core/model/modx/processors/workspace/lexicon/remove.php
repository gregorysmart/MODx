<?php
/**
 * @package modx
 * @subpackage processors.workspace.lexicon
 */
$modx->lexicon->load('lexicon');

if (!$modx->hasPermission('lexicons')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['id'])) return $modx->error->failure($modx->lexicon('entry_err_ns'));
$entry = $modx->getObject('modLexiconEntry',$_POST['id']);
if ($entry == null) {
    return $modx->error->failure(sprintf($modx->lexicon('entry_err_nfs'),$_POST['id']));
}

if ($entry->remove() === false) {
    return $modx->error->failure($modx->lexicon('entry_err_save'));
}

$entry->clearCache();

return $modx->error->success();