<?php
/**
 * @package modx
 * @subpackage processors.workspace.lexicon
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('lexicon');

if (!$modx->hasPermission('lexicons')) $modx->error->failure($modx->lexicon('permission_denied'));

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

$entry->clearCache();

$modx->error->success();