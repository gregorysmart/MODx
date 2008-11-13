<?php
/**
 * @package modx
 * @subpackage processors.security.documentgroup
 */
if (!$modx->hasPermission('access_permissions')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['name'])) $_POST['name'] = $modx->lexicon('document_group_untitled');

$dg = $modx->getObject('modResourceGroup',array('name' => $_POST['name']));
if ($dg != null) return $modx->error->failure($modx->lexicon('document_group_err_already_exists'));

$dg = $modx->newObject('modResourceGroup');
$dg->set('name',$_POST['name']);
if ($dg->save() == false) {
    return $modx->error->failure($modx->lexicon('document_group_err_create'));
}

/* log manager action */
$modx->logManagerAction('new_resource_group','modResourceGroup',$dg->get('id'));

return $modx->error->success();