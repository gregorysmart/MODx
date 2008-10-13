<?php
/**
 * @package modx
 * @subpackage processors.element.tv
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('tv');

if (!$modx->hasPermission('new_template')) $modx->error->failure($modx->lexicon('permission_denied'));

/* get TV */
$old_tv = $modx->getObject('modTemplateVar',$_REQUEST['id']);
if ($old_tv == null) $modx->error->failure($modx->lexicon('tv_err_not_found'));

$old_tv->templates = $old_tv->getMany('modTemplateVarTemplate');
$old_tv->resources = $old_tv->getMany('modTemplateVarResource');
$old_tv->resource_groups = $old_tv->getMany('modTemplateVarResourceGroup');

$newname = isset($_POST['name'])
    ? $_POST['name']
    : $modx->lexicon('duplicate_of').$old_tv->get('name');

/* duplicate TV */
$tv = $modx->newObject('modTemplateVar');
$tv->set('name',$newname);
$tv->fromArray($old_tv->toArray());

if ($tv->save() === false) {
	$modx->error->failure($modx->lexicon('tv_err_duplicate'));
}


foreach ($old_tv->templates as $old_tvt) {
	$new_template = $modx->newObject('modTemplateVarTemplate');
	$new_template->set('tmplvarid',$tv->get('id'));
	$new_template->set('templateid',$old_tvt->get('templateid'));
	$new_template->set('rank',$old_tvt->get('rank'));
	$new_template->save();
}
foreach ($old_tv->resources as $old_tvr) {
	$new_resource = $modx->newObject('modTemplateVarResource');
	$new_resource->set('tmplvarid',$tv->get('id'));
	$new_resource->set('contentid',$old_tvr->get('contentid'));
	$new_resource->set('value',$old_tvr->get('value'));
	$new_resource->save();
}
foreach ($old_tv->resource_groups as $old_tvrg) {
	$new_rgroup = $modx->newObject('modTemplateVarResourceGroup');
	$new_rgroup->set('tmplvarid',$tv->get('id'));
	$new_rgroup->set('documentgroup',$old_tvrg->get('documentgroup'));
	$new_rgroup->save();
}

/* log manager action */
$modx->logManagerAction('tv_duplicate','modTemplateVar',$tv->get('id'));

$modx->error->success('',$tv->get(array('id')));