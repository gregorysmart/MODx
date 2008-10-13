<?php
/**
 * @package modx
 * @subpackage processors.security.access
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('access');

if (!$modx->hasPermission('access_permissions')) $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_REQUEST['type']) || !isset($_REQUEST['id'])) {
    $modx->error->failure($modx->lexicon('access_type_err_ns'));
}

$acl = $modx->getObject($_REQUEST['type'], $_REQUEST['id']);
if ($acl === null) $modx->error->failure($modx->lexicon('access_err_nf'));

if ($acl->remove() == false) {
    $modx->error->failure($modx->lexicon('access_err_remove'));
}

$modx->error->success();