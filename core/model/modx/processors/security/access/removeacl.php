<?php
/**
 * @package modx
 * @subpackage processors.security.access
 */
$modx->lexicon->load('access');

if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_REQUEST['type']) || !isset($_REQUEST['id'])) {
    return $modx->error->failure($modx->lexicon('access_type_err_ns'));
}

$acl = $modx->getObject($_REQUEST['type'], $_REQUEST['id']);
if ($acl === null) return $modx->error->failure($modx->lexicon('access_err_nf'));

if ($acl->remove() == false) {
    return $modx->error->failure($modx->lexicon('access_err_remove'));
}

return $modx->error->success();