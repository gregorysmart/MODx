<?php
/**
 * @package modx
 * @subpackage processors.security.group
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('user');

if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_REQUEST['id'])) return $modx->error->failure($modx->lexicon('user_group_err_not_specified'));

$ug = $modx->getObject('modUserGroup',$_REQUEST['id']);
if ($ug == null) return $modx->error->failure($modx->lexicon('user_group_err_not_found'));

return $modx->error->success('',$ug);