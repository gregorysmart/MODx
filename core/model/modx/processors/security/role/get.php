<?php
/**
 * @package modx
 * @subpackage processors.security.role
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('role');

if (!isset($_REQUEST['id']) || $_REQUEST['id'] == '') {
    $modx->error->failure($modx->lexicon('role_err_ns'));
}
$role = $modx->getObject('modUserGroupRole',$_REQUEST['id']);
if ($role == null) {
    $modx->error->failure(sprintf($modx->lexicon('role_err_nfs'),$_REQUEST['id']));
}

$modx->error->success('',$role);