<?php
/**
 * @package modx
 * @subpackage processors.security.group
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('user');

if (!$modx->hasPermission('access_permissions')) $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['id'])) {
    $modx->error->failure($modx->lexicon('user_group_err_not_specified'));
}
$ug = $modx->getObject('modUserGroup',$_POST['id']);
if ($ug == null) $modx->error->failure($modx->lexicon('user_group_err_not_found'));

$ug->set('name',$_POST['name']);

if ($ug->save() === false) {
    $modx->error->failure($modx->lexicon('user_group_err_save'));
}

/* log manager action */
$modx->logManagerAction('save_user_group','modUserGroup',$ug->get('id'));

$modx->error->success();