<?php
/**
 * @package modx
 * @subpackage processors.element.tv
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('tv');

if (!$modx->hasPermission('new_template')) $modx->error->failure($modx->lexicon('permission_denied'));

// get TV
$old_tv = $modx->getObject('modTemplateVar',$_REQUEST['id']);
if ($old_tv == null) $modx->error->failure($modx->lexicon('tv_err_not_found'));

$old_tv->templates = $old_tv->getMany('modTemplateVarTemplate');
$old_tv->documents = $old_tv->getMany('modTemplateVarResource');
$old_tv->docgroups = $old_tv->getMany('modTemplateVarResourceGroup');

$newname = isset($_POST['name']) 
    ? $_POST['name']
    : $modx->lexicon('duplicate_of').$old_tv->name;

// duplicate TV
$tv = $modx->newObject('modTemplateVar');
$tv->set('type',$old_tv->type);
$tv->set('name',$newname);
$tv->set('caption',$old_tv->caption);
$tv->set('description',$old_tv->description);
$tv->set('editor_type',$old_tv->editor_type);
$tv->set('category',$old_tv->category);
$tv->set('locked',$old_tv->locked);
$tv->set('elements',$old_tv->elements);
$tv->set('rank',$old_tv->rank);
$tv->set('display',$old_tv->display);
$tv->set('display_params',$old_tv->display_params);
$tv->set('default_text',$old_tv->default_text);

if ($tv->save() === false) {
	$modx->error->failure($modx->lexicon('tv_err_duplicate'));
}


foreach ($old_tv->templates as $old_template) {
	$new_template = $modx->newObject('modTemplateVarTemplate');
	$new_template->set('tmplvarid',$tv->id);
	$new_template->set('templateid',$old_template->templateid);
	$new_template->set('rank',$old_template->rank);
	if ($new_template->save() === false) {
		$error->failure($modx->lexicon('tv_err_duplicate_templates'));
	}
}
foreach ($old_tv->documents as $old_document) {
	$new_document = $modx->newObject('modTemplateVarResource');
	$new_document->set('tmplvarid',$tv->id);
	$new_document->set('contentid',$old_document->contentid);
	$new_document->set('value',$old_document->value);
	if ($new_document->save() === false) {
		$error->failure($modx->lexicon('tv_err_duplicate_documents'));
	}
}
foreach ($old_tv->docgroups as $old_docgroup) {
	$new_docgroup = $modx->newObject('modTemplateVarResourceGroup');
	$new_docgroup->set('tmplvarid',$tv->id);
	$new_docgroup->set('documentgroup',$old_docgroup->documentgroup);
	if ($new_docgroup->save() === false) {
		$modx->error->failure($modx->lexicon('tv_err_duplicate_documentgroups'));
	}
}


// log manager action
$modx->logManagerAction('tv_duplicate','modTemplateVar',$tv->id);

$modx->error->success('',$tv->get(array('id')));