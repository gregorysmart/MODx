<?php
/**
 * Load create template page
 *
 * @package modx
 * @subpackage manager.element.tv
 */

if (!$modx->hasPermission('edit_template')) return $modx->error->failure($modx->lexicon('access_denied'));

/* get tv */
$tv = $modx->getObject('modTemplateVar',$_REQUEST['id']);
if ($tv == null) return $modx->error->failure($modx->lexicon('tv_err_not_found'));
if ($tv->get('locked') && !$modx->hasPermission('edit_locked')) {
    return $modx->error->failure($modx->lexicon('tv_err_locked'));
}

$tv->category = $tv->getOne('modCategory');

/* load templates */
$templates = $modx->getCollection('modTemplate');
foreach ($templates as $template) {
	$tmplvar = $modx->getObject('modTemplateVarTemplate',array(
		'templateid' => $template->get('id'),
		'tmplvarid' => $tv->get('id'),
	));
	if ($tmplvar != null) $template->set('checked',true);
}
$modx->smarty->assign('templates',$templates);

$notPublic = false;
$groupsarray = array();
/* fetch permissions for the variable */
$resource_groups = $modx->getCollection('modTemplateVarResourceGroup',array('tmplvarid' => $_REQUEST['id']));
foreach ($resource_groups as $rg) {
    $groupsarray[] = $rg->get('documentgroup');
    $notPublic = true;
}

$rgs = $modx->getCollection('modResourceGroup');
foreach ($rgs as $rg) {
    $rg->set('checked',in_array($rg->get('id'),$groupsarray));
}

$modx->smarty->assign('notPublic',$notPublic);
$modx->smarty->assign('docgroups',$dgs);

/* get available RichText Editors */
$RTEditors = '';
$evtOut = $modx->invokeEvent('OnRichTextEditorRegister',array('forfrontend' => true));
if(is_array($evtOut)) $RTEditors = implode(',',$evtOut);
$modx->smarty->assign('RTEditors',$RTEditors);

/* invoke OnTVFormPrerender event */
$onTVFormPrerender = $modx->invokeEvent('OnTVFormPrerender',array('id' => $_REQUEST['id']));
if(is_array($onTVFormPrerender)) $onTVFormPrerender = implode('',$onTVFormPrerender);
$modx->smarty->assign('onTVFormPrerender',$onTVFormPrerender);

/* invoke OnTVFormRender event */
$onTVFormRender = $modx->invokeEvent('OnTVFormRender',array('id' => $_REQUEST['id']));
if (is_array($onTVFormRender)) $onTVFormRender = implode('',$onTVFormRender);
$modx->smarty->assign('onTVFormRender',$onTVFormRender);

/* check unlock default element properties permission */
$modx->smarty->assign('unlock_element_properties',$modx->hasPermission('unlock_element_properties') ? 1 : 0);

/* assign TV to parser and display template */
$modx->smarty->assign('tv',$tv);
return $modx->smarty->fetch('element/tv/update.tpl');