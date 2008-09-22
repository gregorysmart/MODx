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
$accessClass = $_REQUEST['type'];
$accessId = $_REQUEST['id'];

$aclArray = array();
if ($acl = $modx->getObject($accessClass, $accessId)) {
    $aclArray = $acl->toArray();
}

$modx->error->success('', $aclArray);