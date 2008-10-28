<?php
/**
 * @package modx
 * @subpackage processors.element.module
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('module','category','user');

if (!$modx->hasPermission('new_module')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* category */
if (is_numeric($_POST['category'])) {
    $category = $modx->getObject('modCategory',array('id' => $_POST['category']));
} else {
    $category = $modx->getObject('modCategory',array('category' => $_POST['category']));
}
if ($category == null) {
    $category = $modx->newObject('modCategory');
    if ($_POST['category'] == '' || $_POST['category'] == 'null') {
        $category->set('id',0);
    } else {
        $category->set('category',$_POST['category']);
        $category->save();
    }
}

if ($_POST['name'] == '') $_POST['name'] = $modx->lexicon('module_untitled');

/* invoke OnBeforeModFormSave event */
$modx->invokeEvent('OnBeforeModFormSave',array(
	'mode' => 'new',
	'id' => 0,
));

/* save the new module */
$module = $modx->newObject('modModule');
$module->fromArray($_POST);
$module->set('disabled',isset($_POST['disabled']));
$module->set('wrap',isset($_POST['wrap']));
$module->set('locked',isset($_POST['locked']));
$module->set('enable_resource',isset($_POST['enable_resource']));
$module->set('category',$category->get('id'));
$module->set('enable_sharedparams',isset($_POST['enable_sharedparams']));
$module->set('modulecode',$_POST['post']);

if ($module->save() == false) {
    return $modx->error->failure($modx->lexicon('module_err_save'));
}

/* save user group access permissions
 * :TODO: replace with modAccessModule
 * delete old permissions on the module
 */
$module->ugs = $module->getMany('modModuleUserGroup');
foreach ($module->ugs as $ug) {
	$ug->remove();
}

if (is_array($_POST['usrgroups'])) {
	foreach ($_POST['usrgroups'] as $ugkey => $value) {
		$ug = $modx->newObject('modModuleUserGroup');
		$ug->set('module',$module->get('id'));
		$ug->set('usergroup',$value);
		if ($ug->save() == false) {
			return $modx->error->failure($modx->lexicon('user_err_save_access_permissions'));
        }
	}
}

/* invoke OnModFormSave event */
$modx->invokeEvent('OnModFormSave',array(
	'mode' => 'new',
	'id' => $module->get('id'),
));

/* log manager action */
$modx->logManagerAction('module_create','modModule',$module->get('id'));

/* empty cache */
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

return $modx->error->success('',$module->get(array_diff(array_keys($module->_fields), array('modulecode'))));