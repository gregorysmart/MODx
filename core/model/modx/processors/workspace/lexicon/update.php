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

$old_namespace = $entry->namespace;
$old_focus = $entry->focus;

if (!isset($_POST['name']) || $_POST['name'] == '') {
    $modx->error->failure($modx->lexicon('entry_err_ns_name'));
}

$entry->set('name',$_POST['name']);
$entry->set('value',$_POST['value']);
$entry->set('editedon',date('Y-m-d h:i:s'));
$entry->set('namespace',$_POST['namespace']);
$entry->set('focus',$_POST['focus']);
$entry->set('language',$_POST['language']);

if (!$entry->save()) $modx->error->failure($modx->lexicon('entry_err_save'));

$r = $modx->lexicon->clearCache($old_namespace.'/'.$old_focus.'.cache.php');
$r = $modx->lexicon->clearCache($entry->get('namespace').'/'.$entry->get('focus').'.cache.php');

$modx->error->success();