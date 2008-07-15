<?php
/**
 * @package modx
 * @subpackage processors.element.module
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('module');

if (!$modx->hasPermission('new_module')) $modx->error->failure($modx->lexicon('permission_denied'));

// create globally unique identifiers (guid)
function createGUID(){
	srand((double)microtime()*1000000);
	$r = rand() ;
	$u = uniqid(getmypid() . $r . (double)microtime()*1000000,1);
	$m = md5 ($u);
	return $m;
}

// Get old module and dependencies
$old_module = $modx->getObject('modModule',$_REQUEST['id']);
if ($old_module == null) $modx->error->failure($modx->lexicon('module_err_not_found'));

$old_module->dependencies = $old_module->getMany('modModuleDepobj');
$old_module->usergroups = $old_module->getMany('modModuleUserGroup');

$newname = isset($_POST['name']) 
    ? $_POST['name']
    : $modx->lexicon('duplicate_of').$old_module->name;

// duplicate
$module = $modx->newObject('modModule');
$module->set('name',$newname);
$module->set('description',$old_module->description);
$module->set('editor_type',$old_module->editor_type);
$module->set('disabled',$old_module->disabled);
$module->set('category',$old_module->category);
$module->set('wrap',$old_module->wrap);
$module->set('locked',$old_module->locked);
$module->set('icon',$old_module->icon);
$module->set('enable_resource',$old_module->enable_resource);
$module->set('resourcefile',$old_module->resourcefile);
$module->set('createdon',$old_module->createdon);
$module->set('editedon',$old_module->editedon);
$module->set('guid',createGUID());
$module->set('enable_sharedparams',$old_module->enable_sharedparams);
$module->set('properties',$old_module->properties);
$module->set('modulecode',$old_module->modulecode);

if ($module->save() === false) {
	$modx->error->failure($modx->lexicon('module_err_duplicate'));
}

foreach ($old_module->dependencies as $old_dep) {
	$new_dep = $modx->newObject('modModuleDepobj');
	$new_dep->set('module',$module->id);
	$new_dep->set('resource',$old_dep->resource);
	$new_dep->set('type',$old_dep->type);
	if (!$new_dep->save())
		$modx->error->failure($modx->lexicon('module_err_duplicate_dependancy'));
}

foreach ($old_module->usergroups as $old_ug) {
	$new_ug = $modx->newObject('modModuleUserGroup');
	$new_ug->set('module',$module->id);
	$new_ug->set('usergroup',$old_ug->usergroup);
	if (!$new_ug->save())
		$modx->error->failure($modx->lexicon('module_err_duplicate_usergroup'));
}

// log manager action
$modx->logManagerAction('module_duplicate','modModule',$module->id);

$modx->error->success('',$module->get(array_diff(array_keys($module->_fields), array('modulecode'))));