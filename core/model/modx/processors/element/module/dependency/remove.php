<?php
/**
 * @package modx
 * @subpackage processors.element.module.dependency
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('module');

if (!$modx->hasPermission('delete_module')) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}

/* get dependency */
if (!isset($_POST['id'])) return $modx->error->failure($modx->lexicon('module_dep_err_ns'));
$dep = $modx->getObject('modModuleDepobj',$_POST['id']);
if ($dep == null) return $modx->error->failure($modx->lexicon('module_dep_err_nf'));

/* remove dependency */
if ($dep->remove() == false) {
    return $modx->error->failure($modx->lexicon('module_dep_err_remove'));
}

/* log manager action */
$modx->logManagerAction('module_depobj_delete','modModuleDepobj',$dep->get('id'));

return $modx->error->success();