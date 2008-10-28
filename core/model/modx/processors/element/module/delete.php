<?php
/**
 * @package modx
 * @subpackage processors.element.module
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('module');

if (!$modx->hasPermission('delete_module')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* get module and related tables */
$module = $modx->getObject('modModule',$_REQUEST['id']);
if ($module == null) return $modx->error->failure($modx->lexicon('module_err_not_found'));

$module->dependencies = $module->getMany('modModuleDepobj');
$module->usergroups = $module->getMany('modModuleUserGroup');

/* invoke OnBeforeModFormDelete event */
$modx->invokeEvent('OnBeforeModFormDelete',array(
	'id' => $module->get('id'),
));


/* delete related table rows */
foreach ($module->dependencies as $dep) {
	$dep->remove();
}

foreach ($module->usergroups as $ug) {
	$ug->remove();
}

/* delete module */
if ($module->remove() == false) {
    return $modx->error->failure($modx->lexicon('module_err_delete'));
}

/* invoke OnModFormDelete event */
$modx->invokeEvent('OnModFormDelete',array(
	'id' => $module->get('id'),
));

/* log manager action */
$modx->logManagerAction('module_delete','modModule',$module->get('id'));

/* empty cache */
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

return $modx->error->success();