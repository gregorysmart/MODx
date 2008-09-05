<?php
/**
 * Load create template page 
 * 
 * @package modx
 * @subpackage manager.element.tv
 */

if (!$modx->hasPermission('edit_template')) $modx->error->failure($modx->lexicon('access_denied'));

// check to see the variable editor isn't locked
if ($msg= $modx->checkForLocks($modx->getLoginUserID(),301,'template variable')) $modx->error->failure($msg);

// get tv
$tv = $modx->getObject('modTemplateVar',$_REQUEST['id']);
if ($tv == null) $modx->error->failure($modx->lexicon('tv_err_not_found'));
if ($tv->locked && !$modx->hasPermission('edit_locked')) $error->failure($modx->lexicon('tv_err_locked'));

$tv->category = $tv->getOne('modCategory');

// load templates
$templates = $modx->getCollection('modTemplate');
foreach ($templates as $template) {
	$tmplvar = $modx->getObject('modTemplateVarTemplate',array(
		'templateid' => $template->id,
		'tmplvarid' => $tv->id,
	));
	if ($tmplvar != null) $template->set('checked',true);
}
$modx->smarty->assign('templates',$templates);

$notPublic = false;
$groupsarray = array();
// fetch permissions for the variable
$docgroups = $modx->getCollection('modTemplateVarResourceGroup',array('tmplvarid' => $_REQUEST['id']));
foreach ($docgroups as $dg) {
    $groupsarray[] = $dg->documentgroup;
    $notPublic = true;
}

$dgs = $modx->getCollection('modResourceGroup');
foreach ($dgs as $dg) {
    $dg->set('checked',in_array($dg->id,$groupsarray));
}

$modx->smarty->assign('notPublic',$notPublic);
$modx->smarty->assign('docgroups',$dgs);

// get available RichText Editors
$RTEditors = '';
$evtOut = $modx->invokeEvent('OnRichTextEditorRegister',array('forfrontend' => 1));
if(is_array($evtOut)) $RTEditors = implode(',',$evtOut);
$modx->smarty->assign('RTEditors',$RTEditors);

// invoke OnTVFormPrerender event
$onTVFormPrerender = $modx->invokeEvent('OnTVFormPrerender',array('id' => $_REQUEST['id']));
if(is_array($onTVFormPrerender)) $onTVFormPrerender = implode('',$onTVFormPrerender);
$modx->smarty->assign('onTVFormPrerender',$onTVFormPrerender);

// invoke OnTVFormRender event
$onTVFormRender = $modx->invokeEvent('OnTVFormRender',array('id' => $_REQUEST['id']));
if (is_array($onTVFormRender)) $onTVFormRender = implode('',$onTVFormRender);
$modx->smarty->assign('onTVFormRender',$onTVFormRender);

// assign TV to parser and display template
$modx->smarty->assign('tv',$tv);
$modx->smarty->display('element/tv/update.tpl');