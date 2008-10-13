<?php
/**
 * @package modx
 * @subpackage processors.security.group
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('user');

if (!$modx->hasPermission('access_permissions')) $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['name'])) $_POST['name'] = $modx->lexicon('user_group_untitled');
if (!isset($_POST['parent'])) $_POST['parent'] = 0;

$ug = $modx->getObject('modUserGroup',array('name' => $_POST['name']));
if ($ug != null) $modx->error->failure($modx->lexicon('user_group_err_already_exists'));

$ug = $modx->newObject('modUserGroup');
$ug->set('name',$_POST['name']);
$ug->set('parent',$_POST['parent']);
if ($ug->save() == false) {
    $modx->error->failure($modx->lexicon('user_group_err_create'));
}

/* log manager action */
$modx->logManagerAction('new_user_group','modUserGroup',$ug->get('id'));

$modx->error->success('',$ug);