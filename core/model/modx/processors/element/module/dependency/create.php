<?php
/**
 * @package modx
 * @subpackage processors.element.module.dependency
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('module');

if (!$modx->hasPermission('edit_module')) {
    return $modx->error->failure($modx->lexicon('permission_denied'));
}

if (!isset($_POST['module'])) return $modx->error->failure($modx->lexicon('module_err_ns'));
$module = $modx->getObject('modModule',$_POST['module']);
if ($module == null) return $modx->error->failure($modx->lexicon('module_err_nf'));

$typemap = array(
    'modChunk' => 10,
    'modDocument' => 20,
    'modResource' => 20,
    'modPlugin' => 30,
    'modSnippet' => 40,
    'modTemplate' => 50,
    'modTemplateVar' => 60,
);

if (!isset($typemap[$_POST['classKey']])) {
    return $modx->error->failure($modx->lexicon('module_err_dep_save'));
}

$moduleDep = $modx->newObject('modModuleDepobj');
$moduleDep->set('module',$module->get('id'));
$moduleDep->set('resource',$_POST['object']);
$moduleDep->set('type',$typemap[$_POST['classKey']]);

if ($moduleDep->save() == false) {
    return $modx->error->failure($modx->lexicon('module_err_dep_save'));
}

/* empty cache */
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

/* log manager action */
$modx->logManagerAction('module_depobj_create','modModuleDepobj',$moduleDep->get('id'));

return $modx->error->success();