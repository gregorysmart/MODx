<?php
/**
 * @package modx
 * @subpackage processors.workspace.lexicon
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('lexicon');

if (!isset($_POST['id'])) $modx->error->failure($modx->lexicon('focus_err_ns'));
$focus = $modx->getObject('modLexiconFocus',array(
    'id' => $_POST['id'],
));
if ($focus == null) $modx->error->failure($modx->lexicon('focus_err_nf'));

if ($focus->remove() === false) {
    $modx->error->failure($modx->lexicon('focus_err_remove'));
}

$modx->error->success();