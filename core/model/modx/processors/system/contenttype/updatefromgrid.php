<?php
/**
 * @package modx
 * @subpackage processors.system.contenttype
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('content_type');

$_DATA = $modx->fromJSON($_POST['data']);

if (!isset($_DATA['id'])) $modx->error->failure($modx->lexicon('content_type_err_ns'));
$ct = $modx->getObject('modContentType',$_DATA['id']);
if ($ct == null) {
    $modx->error->failure(sprintf($modx->lexicon('content_type_err_nfs'),$_DATA['id']));
}

$ct->fromArray($_DATA);
if (!$ct->save()) {
    $modx->error->checkValidation($ct);
    $modx->error->failure($modx->lexicon('content_type_err_save'));
}

// log manager action
$modx->logManagerAction('content_type_save','modContentType',$ct->id);

$modx->error->success('',$ct);