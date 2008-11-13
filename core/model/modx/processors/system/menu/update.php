<?php
/**
 * @package modx
 * @subpackage processors.system.menu
 */
$modx->lexicon->load('action','menu');

if (!$modx->hasPermission('menus')) return $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_REQUEST['id'])) return $modx->error->failure($modx->lexicon('menu_err_ns'));
$menu = $modx->getObject('modMenu',$_REQUEST['id']);
if ($menu == null) return $modx->error->failure($modx->lexicon('menu_err_nf'));

if (!isset($_POST['action_id'])) return $modx->error->failure($modx->lexicon('action_err_ns'));
if ($_POST['action_id'] == 0) {
	$action = $modx->newObject('modAction');
	$action->set('id',0);
} else {
	$action = $modx->getObject('modAction',$_POST['action_id']);
	if ($action == null) return $modx->error->failure($modx->lexicon('action_err_nf'));
}
if (!isset($_POST['parent'])) return $modx->error->failure($modx->lexicon('menu_parent_err_ns'));
if ($_POST['parent'] == 0) {
	$parent = $modx->newObject('modMenu');
	$parent->set('id',0);
} else {
	$parent = $modx->getObject('modMenu',$_POST['parent']);
	if ($parent == null) return $modx->error->failure($modx->lexicon('menu_parent_err_nf'));
}

$menu->set('parent',$parent->get('id'));
$menu->set('action',$action->get('id'));
$menu->set('text',isset($_POST['text']) ? $_POST['text'] : '');
$menu->set('icon',isset($_POST['icon']) ? $_POST['icon'] : '');
$menu->set('params',isset($_POST['params']) ? $_POST['params'] : '');
$menu->set('handler',isset($_POST['handler']) ? $_POST['handler'] : '');

if (!$menu->save()) return $modx->error->failure($modx->lexicon('menu_err_save'));

/* log manager action */
$modx->logManagerAction('menu_update','modMenu',$menu->get('id'));

return $modx->error->success();