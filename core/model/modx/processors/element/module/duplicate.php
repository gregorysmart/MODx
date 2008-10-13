<?php
/**
 * @package modx
 * @subpackage processors.element.module
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('module');

if (!$modx->hasPermission('new_module')) $modx->error->failure($modx->lexicon('permission_denied'));

/* create globally unique identifiers (guid) */
function createGUID(){
	srand((double)microtime()*1000000);
	$r = rand() ;
	$u = uniqid(getmypid() . $r . (double)microtime()*1000000,1);
	$m = md5 ($u);
	return $m;
}

/* get old module and dependencies */
$old_module = $modx->getObject('modModule',$_REQUEST['id']);
if ($old_module == null) $modx->error->failure($modx->lexicon('module_err_not_found'));

$old_module->dependencies = $old_module->getMany('modModuleDepobj');
$old_module->usergroups = $old_module->getMany('modModuleUserGroup');

$newname = isset($_POST['name'])
    ? $_POST['name']
    : $modx->lexicon('duplicate_of').$old_module->get('name');

/* duplicate */
$module = $modx->newObject('modModule');
$module->fromArray($old_module->toArray());
$module->set('name',$newname);
$module->set('guid',createGUID());

if ($module->save() === false) {
	$modx->error->failure($modx->lexicon('module_err_duplicate'));
}

foreach ($old_module->dependencies as $old_dep) {
	$new_dep = $modx->newObject('modModuleDepobj');
	$new_dep->set('module',$module->get('id'));
	$new_dep->set('resource',$old_dep->get('resource'));
	$new_dep->set('type',$old_dep->get('type'));
	if ($new_dep->save() == false) {
		$modx->error->failure($modx->lexicon('module_err_duplicate_dependancy'));
    }
}

foreach ($old_module->usergroups as $old_ug) {
	$new_ug = $modx->newObject('modModuleUserGroup');
	$new_ug->set('module',$module->get('id'));
	$new_ug->set('usergroup',$old_ug->get('usergroup'));
	if ($new_ug->save() == false) {
		$modx->error->failure($modx->lexicon('module_err_duplicate_usergroup'));
    }
}

/* log manager action */
$modx->logManagerAction('module_duplicate','modModule',$module->get('id'));

$modx->error->success('',$module->get(array_diff(array_keys($module->_fields), array('modulecode'))));