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

$focus = $modx->newObject('modLexiconFocus');
$focus->set('name',$_POST['name']);
$focus->set('namespace',$namespace->name);

if ($focus->save() === false) {
	$modx->error->failure($modx->lexicon('focus_err_create'));
}

$modx->error->success();