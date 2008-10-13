<?php
/**
 * @package modx
 * @subpackage processors.security.documentgroup
 */
require_once MODX_PROCESSORS_PATH.'index.php';
if (!$modx->hasPermission('access_permissions')) $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['document_group'])) $modx->error->failure($modx->lexicon('document_group_err_not_specified'));
if (!isset($_POST['document'])) $modx->error->failure($modx->lexicon('document_err_not_specified'));

$dgd = $modx->getObject('modResourceGroupResource',array(
	'document_group' => $_POST['document_group'],
	'document' => $_POST['document'],
));
if ($dgd == null) $modx->error->failure($modx->lexicon('document_group_document_err_not_found'));

if ($dgd->remove() == false) {
    $modx->error->failure($modx->lexicon('document_group_document_err_remove'));
}

$modx->error->success();