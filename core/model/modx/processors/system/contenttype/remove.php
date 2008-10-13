<?php
/**
 * @package modx
 * @subpackage processors.system.contenttype
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('content_type');

if (!$modx->hasPermission('content_types')) $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['id'])) $modx->error->failure($modx->lexicon('content_type_err_ns'));
$ct = $modx->getObject('modContentType',$_POST['id']);
if ($ct == null) {
    $modx->error->failure(sprintf($modx->lexicon('content_type_err_nfs'),$_POST['id']));
}

if ($ct->remove() == false) {
    $modx->error->failure($modx->lexicon('content_type_err_remove'));
}

/* log manager action */
$modx->logManagerAction('content_type_delete','modContentType',$ct->get('id'));

$modx->error->success('',$ct);