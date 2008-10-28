<?php
/**
 * @package modx
 * @subpackage processors.security.documentgroup
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('user');

if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['id'])) return $modx->error->failure($modx->lexicon('document_group_err_not_specified'));

$dg = $modx->getObject('modResourceGroup',$_POST['id']);
if ($dg == null) return $modx->error->failure($modx->lexicon('document_group_err_not_found'));

if (!$dg->remove()) return $modx->error->failure($modx->lexicon('document_group_err_remove'));

/* log manager action */
$modx->logManagerAction('delete_resource_group','modResourceGroup',$dg->get('id'));

return $modx->error->success();