<?php
/**
 * @package modx
 * @subpackage processors.security.role
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('role');

if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_REQUEST['id']) || $_REQUEST['id'] == '') {
    return $modx->error->failure($modx->lexicon('role_err_ns'));
}
$role = $modx->getObject('modUserGroupRole',$_REQUEST['id']);
if ($role == null) {
    return $modx->error->failure(sprintf($modx->lexicon('role_err_nfs'),$_REQUEST['id']));
}

return $modx->error->success('',$role);