<?php
/**
 * Loads the create resource page
 *
 * @package modx
 * @subpackage manager.resource
 */
if (!$modx->hasPermission('new_document')) return $modx->error->failure($modx->lexicon('access_denied'));

$resourceClass= isset ($_REQUEST['class_key']) ? $_REQUEST['class_key'] : 'modDocument';
$resourceDir= strtolower(substr($resourceClass, 3));

$delegateView= dirname(__FILE__) . '/' . $resourceDir . '/' . basename(__FILE__);
if (file_exists($delegateView)) {
    $overridden= include_once ($delegateView);
    if ($overridden !== false) {
        return;
    }
}

$resource = $modx->newObject($resourceClass);

/* handle switch template */
if (isset ($_REQUEST['newtemplate'])) {
	foreach ($_POST as $key => $val) {
		$resource->set($key,$val);
	}
    $resource->set('content',$_POST['ta']);
    $pub_date = $resource->get('pub_date');
    if (!empty($pub_date) && $pub_date != '') {
        list ($d, $m, $Y, $H, $M, $S) = sscanf($pub_date, "%2d-%2d-%4d %2d:%2d:%2d");
        $pub_date = strtotime("$m/$d/$Y $H:$M:$S");
        $resource->set('pub_date',$pub_date);
    }
    $unpub_date = $resource->get('unpub_date');
    if (!empty($unpub_date) && $unpub_date != '') {
        list ($d, $m, $Y, $H, $M, $S) = sscanf($unpub_date, "%2d-%2d-%4d %2d:%2d:%2d");
        $unpub_date = strtotime("$m/$d/$Y $H:$M:$S");
        $resource->set('unpub_date',$unpub_date);
    }
}

/* invoke OnDocFormPrerender event */
$onDocFormPrerender = $modx->invokeEvent('OnDocFormPrerender',array('id' => 0));
if (is_array($onDocFormPrerender)) {
    $onDocFormPrerender = implode('',$onDocFormPrerender);
}
$modx->smarty->assign('onDocFormPrerender',$onDocFormPrerender);

/* handle default parent */
$parentname = $modx->config['site_name'];
$resource->set('parent',0);
if (isset ($_REQUEST['id'])) {
	if ($_REQUEST['id'] == 0) {
		$parentname = $modx->config['site_name'];
	} else {
		$parent = $modx->getObject('modResource',$_REQUEST['id']);
		if ($parent != null) {
		  $parentname = $parent->get('pagetitle');
		  $resource->set('parent',$parent->get('id'));
        }
	}
}
$modx->smarty->assign('parentname',$parentname);

/* set permissions on the resource based on the permissions of the parent resource
 * TODO: get working in revo, move to get processor
 */
$groupsarray = array ();
if (!empty ($_REQUEST['parent'])) {
    $dgds = $modx->getCollection('modResourceGroupResource',array('document' => $_REQUEST['parent']));
    foreach ($dgds as $dgd) {
        $groupsarray[$dgd->get('id')] = $dgd->get('document_group');
    }
}
$c = $modx->newQuery('modResourceGroup');
$c->sortby('name','ASC');
$docgroups = $modx->getCollection('modResourceGroup',$c);
foreach ($docgroups as $docgroup) {
    $checked = in_array($docgroup->get('id'),$groupsarray);
    $docgroup->set('selected',$checked);
}
$modx->smarty->assign('docgroups',$docgroups);
$modx->smarty->assign('hasdocgroups',count($docgroups) > 0);


/* invoke OnDocFormRender event */
$onDocFormRender = $modx->invokeEvent('OnDocFormRender',array('id' => 0));
if (is_array($onDocFormRender)) {
    $onDocFormRender = implode('',$onDocFormRender);
}
$modx->smarty->assign('onDocFormRender',$onDocFormRender);


/*
 *  Initialize RichText Editor
 */
/* Set which RTE */
$rte = isset($_REQUEST['which_editor']) ? $_REQUEST['which_editor'] : $modx->config['which_editor'];
$modx->smarty->assign('which_editor',$rte);
if ($modx->config['use_editor']) {
    /* invoke OnRichTextEditorRegister event */
    $text_editors = $modx->invokeEvent('OnRichTextEditorRegister');
    $modx->smarty->assign('text_editors',$text_editors);

    $replace_richtexteditor = array('ta');
    $modx->smarty->assign('replace_richtexteditor',$replace_richtexteditor);

	/* invoke OnRichTextEditorInit event */
	$onRichTextEditorInit = $modx->invokeEvent('OnRichTextEditorInit',array(
		'editor' => $rte,
		'elements' => $replace_richtexteditor,
	));
	if (is_array($onRichTextEditorInit)) {
		$onRichTextEditorInit = implode('',$onRichTextEditorInit);
		$modx->smarty->assign('onRichTextEditorInit',$onRichTextEditorInit);
    }
}

$modx->smarty->assign('resource',$resource);
$modx->smarty->display('resource/create.tpl');
