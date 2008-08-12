<?php
/**
 * @package modx
 * @subpackage processors.workspace.lexicon
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('lexicon');

if (!isset($_POST['name'])) $modx->error->failure($modx->lexicon('focus_err_ns'));
$focus = $modx->newObject('modLexiconFocus',$_POST['name']);
if ($focus == null) $modx->error->failure($modx->lexicon('focus_err_nf'));

if ($focus->remove() === false) {
    $modx->error->failure($modx->lexicon('focus_err_remove'));
}

$modx->error->success();