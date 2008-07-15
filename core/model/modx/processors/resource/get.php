<?php
/**
 * @package modx
 * @subpackage processors.resource
 */

require_once MODX_PROCESSORS_PATH . 'index.php';
$modx->lexicon->load('resource');

// get resource
if (!isset($_REQUEST['id'])) {
    $modx->error->failure($modx->lexicon('document_not_specified'));
}
$resource = $modx->getObject('modResource', $_REQUEST['id']);
if ($resource == null) {
    $modx->error->failure($modx->lexicon('document_not_found'));
}

$modx->error->success('',$resource);