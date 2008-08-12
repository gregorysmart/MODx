<?php
/**
 * @package modx
 * @subpackage processors.workspace.lexicon
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('lexicon');

$_DATA = $modx->fromJSON($_POST['data']);

if (!isset($_DATA['id'])) $modx->error->failure($modx->lexicon('entry_err_ns'));
$entry = $modx->getObject('modLexiconEntry',$_DATA['id']);
if ($entry == null) {
    $modx->error->failure(sprintf($modx->lexicon('entry_err_nfs'),$_DATA['id']));
}

if (!isset($_DATA['name']) || $_DATA['name'] == '') {
    $modx->error->failure($modx->lexicon('entry_err_ns_name'));
}

$entry->set('name',$_DATA['name']);
$entry->set('value',$_DATA['value']);
$entry->set('editedon',date('Y-m-d h:i:s'));

if (!$entry->save()) $modx->error->failure($modx->lexicon('entry_err_save'));

$r = $modx->lexicon->clearCache($entry->namespace.'/'.$entry->focus.'.cache.php');

$modx->error->success();