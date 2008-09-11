<?php
/**
 * @package modx
 * @subpackage processors.workspace.lexicon
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('lexicon');

$_DATA = $modx->fromJSON($_POST['data']);

if (!isset($_DATA['id'])) $modx->error->failure($modx->lexicon('focus_err_ns'));
$focus = $modx->newObject('modLexiconFocus',$_DATA['id']);
if ($focus == null) $modx->error->failure($modx->lexicon('focus_err_nf'));

if (!isset($_DATA['namespace'])) $modx->error->failure($modx->lexicon('namespace_err_ns'));
$namespace = $modx->newObject('modNamespace',$_DATA['namespace']);
if ($namespace == null) $modx->error->failure($modx->lexicon('namespace_err_nf'));

$focus->set('namespace',$namespace->name);

if ($focus->save() === false) {
    $modx->error->failure($modx->lexicon('focus_err_save'));
}

$modx->error->success();