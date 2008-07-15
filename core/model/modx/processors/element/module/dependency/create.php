<?php
/**
 * @package modx
 * @subpackage processors.element.module.dependency
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('module');

if (!$modx->hasPermission('edit_module')) $error->failure($modx->lexicon('permission_denied'));
if (!isset($_POST['id']) || !isset($_POST['mid'])) $error->failure($modx->lexicon('module_err_dep_save'));

$id_array = explode('_',$_POST['id']);
$element_type = $id_array[1];
$element_id = $id_array[3];

$typemap = array(
	'chunk' => 10,
	'document' => 20,
	'plugin' => 30,
	'snippet' => 40,
	'template' => 50,
	'tv' => 60
);

$moduleDep = $modx->newObject('modModuleDepobj');
$moduleDep->set('module',$_POST['mid']);
$moduleDep->set('resource',$element_id);
$moduleDep->set('type',$typemap[$element_type]);

if (!$moduleDep->save()) $error->failure($modx->lexicon('module_err_dep_save'));

// empty cache
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

// log manager action
$modx->logManagerAction('module_depobj_create','modModuleDepobj',$moduleDep->id);

$error->success();