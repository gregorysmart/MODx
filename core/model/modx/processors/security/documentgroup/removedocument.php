<?php
/**
 * @package modx
 * @subpackage processors.security.documentgroup
 */

require_once MODX_PROCESSORS_PATH.'index.php';

if (!isset($_POST['document_group'])) $error->failure($modx->lexicon('document_group_err_not_specified'));
if (!isset($_POST['document'])) $error->failure($modx->lexicon('document_err_not_specified'));

$dgd = $modx->getObject('modResourceGroupResource',array(
	'document_group' => $_POST['document_group'],
	'document' => $_POST['document'],
));
if ($dgd == NULL) $error->failure($modx->lexicon('document_group_document_err_not_found'));

if (!$dgd->remove()) $error->failure($modx->lexicon('document_group_document_err_remove'));

$error->success();