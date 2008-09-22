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
if (!$resource->checkPolicy('view')) $modx->error->failure($modx->lexicon('permission_denied'));

$ra = $resource->toArray();
$ra['pub_date'] = $ra['pub_date'] != '0' ? strftime('%Y-%m-%d',$ra['pub_date']) : '';
$ra['unpub_date'] = $ra['unpub_date'] != '0' ? strftime('%Y-%m-%d',$ra['unpub_date']) : '';

$modx->error->success('',$ra);