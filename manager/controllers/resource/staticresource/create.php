<?php
// check permissions
if (!$modx->hasPermission('new_document')) $error->failure($modx->lexicon('access_denied'));

$resourceClass= isset ($_REQUEST['class_key']) ? $_REQUEST['class_key'] : 'modStaticResource';
$resourceDir= strtolower(substr($resourceClass, 3));

$delegateView= dirname(__FILE__) . '/' . $resourceDir . '/' . basename(__FILE__);
if (file_exists($delegateView)) {
    $overridden= include_once ($delegateView);
    if ($overridden !== false) {
        return;
    }
}

$resource = $modx->newObject($resourceClass);

// get document groups for current user
if ($_SESSION['mgrDocgroups']) $docgrp = implode(',',$_SESSION['mgrDocgroups']);

// restore saved form
$formRestored = false;
if ($modx->request->hasFormValues()) {
    $modx->request->loadFormValues();
    $formRestored = true;
}

if ($formRestored == true || isset ($_REQUEST['newtemplate'])) {
	foreach ($_POST as $key => $val) {
		$resource->set($key,$val);
	}
    $resource->set('content',$_POST['ta']);
    if (!empty ($resource->pub_date) && $resource->pub_date != '') {
        $pub_date = $resource->pub_date;
        list ($d, $m, $Y, $H, $M, $S) = sscanf($pub_date, "%2d-%2d-%4d %2d:%2d:%2d");
        $pub_date = strtotime("$m/$d/$Y $H:$M:$S");
        $resource->set('pub_date',$pub_date);
    }
    if (!empty ($resource->unpub_date) && $resource->pub_date != '') {
        $unpub_date = $resource->unpub_date;
        list ($d, $m, $Y, $H, $M, $S) = sscanf($unpub_date, "%2d-%2d-%4d %2d:%2d:%2d");
        $unpub_date = strtotime("$m/$d/$Y $H:$M:$S");
        $resource->set('unpub_date',$unpub_date);
    }
}

// increase menu index if this is a new document
if (!isset($modx->config['auto_menuindex']) || $modx->config['auto_menuindex'])
	$menuindex = $modx->getCount('modResource',array('parent' => intval($_REQUEST['pid'])));
$resource->set('menuindex',isset($menuindex) ? $menuindex : 0);


// invoke OnDocFormPrerender event
$onDocFormPrerender = $modx->invokeEvent('OnDocFormPrerender',array('id' => 0));
if (is_array($onDocFormPrerender))
    $onDocFormPrerender = implode('',$onDocFormPrerender);
$modx->smarty->assign('onDocFormPrerender',$onDocFormPrerender);

$c = $modx->newQuery('modTemplate');
$c = $c->sortby('templatename','ASC');
$templates = $modx->getCollection('modTemplate',$c);
$modx->smarty->assign('templates',$templates);

// PARENT DOCUMENT
if (isset ($_REQUEST['id'])) {
	if ($_REQUEST['id'] == 0) {
		$parentname = $modx->config['site_name'];
	} else {
		$parent = $modx->getObject('modResource',$_REQUEST['id']);
		if ($parent == NULL) {
			$e->setError(8);
			$e->dumpError();
		}
		$parentname = $parent->pagetitle;
		$resource->set('parent',$parent->id);
	}
} else {
	$parentname = $modx->config['site_name'];
    $resource->set('parent',0);
}
$modx->smarty->assign('parentname',$parentname);

// KEYWORDS AND METATAGS
if($modx->hasPermission('edit_doc_metatags')) {

	// get list of site keywords - code by stevew! modified by Raymond
	$keywords = array ();
	$c = $modx->newQuery('modKeyword');
	$c = $c->sortby('keyword','ASC');
	$keywords = $modx->getCollection('modKeyword',$c);
	$modx->smarty->assign('keywords',$keywords);

	// get list of site META tags
	$metatags = $modx->getCollection('modMetatag');
	$modx->smarty->assign('metatags',$metatags);

}

$template = $modx->config['default_template'];
if (isset ($_REQUEST['newtemplate'])) {
    $template = $_REQUEST['newtemplate'];
}

// Template Variables
$categories = $modx->getCollection('modCategory');
// add in uncategorized
$emptycat = $modx->newObject('modCategory');
$emptycat->set('category','uncategorized');
$emptycat->id = 0;
$categories[] = $emptycat;

foreach ($categories as $catKey => $category) {
	$c = new xPDOCriteria($modx,'
		SELECT
			DISTINCT tv.*,
			tv.default_text AS value
		FROM '.$modx->getTableName('modTemplateVar').' AS tv

			INNER JOIN '.$modx->getTableName('modTemplateVarTemplate').' AS tvtpl
			ON tvtpl.tmplvarid = tv.id
			AND tvtpl.templateid = :template

			LEFT JOIN '.$modx->getTableName('modTemplateVarResourceGroup').' AS tva
			ON tva.tmplvarid = tv.id

		WHERE
			tv.category = :category
		AND (
			1 = :mgrRole
			OR ISNULL(tva.documentgroup)
		)
		ORDER BY tvtpl.rank,tv.rank
	',array(
		':mgrRole' => $_SESSION['mgrRole'],
		':template' => $template,
		':category' => $category->id,
	));
	$tvs = $modx->getCollection('modTemplateVar',$c);

	if (count($tvs) > 0) {

		foreach ($tvs as $tv) {
			// go through and display all the document variables
			if ($tv->type == 'richtext' || $tv->type == 'htmlarea') { // htmlarea for backward compatibility
				if (is_array($replace_richtexteditor))
					$replace_richtexteditor = array_merge($replace_richtexteditor, array (
						'tv' . $tv->id
					));
				else
					$replace_richtexteditor = array (
						'tv' . $tv->id
					);
			}
            $fe = $tv->renderInput($resource->id);

			$tv->set('formElement',$fe);
		} //loop through all template variables
	} // end if count($tvs) > 0

	$categories[$catKey]->tvs = $tvs;
} // end category loop
$modx->smarty->assign('categories',$categories);


$groupsarray = array ();
// set permissions on the document based on the permissions of the parent document
if (!empty ($_REQUEST['pid'])) {
    $dgds = $modx->getCollection('modResourceGroupResource',array('document' => $_REQUEST['pid']));
    foreach ($dgds as $dgd)
        $groupsarray[$dgd->id] = $dgd->document_group;
}

// retain selected doc groups between post
if (isset($_POST['docgroups'])) {
    $groupsarray = array_merge($groupsarray, $_POST['docgroups']);
}

$c = $modx->newQuery('modResourceGroup');
$c = $c->sortby('name','ASC');
$docgroups = $modx->getCollection('modResourceGroup',$c);
foreach ($docgroups as $docgroup) {
    $checked = in_array($docgroup->id,$groupsarray);
    $docgroup->set('selected',$checked);
}

$modx->smarty->assign('docgroups',$docgroups);


// invoke OnDocFormRender event
$onDocFormRender = $modx->invokeEvent('OnDocFormRender',array('id' => 0));
if (is_array($onDocFormRender))
    $onDocFormRender = implode('',$onDocFormRender);
$modx->smarty->assign('onDocFormRender',$onDocFormRender);


/**
 *  Initialize RichText Editor
 *  orig MODIFIED BY S.BRENNAN for DocVars
 */
if ($modx->config['use_editor'] == 1) {
	if (is_array($replace_richtexteditor)) {
		// invoke OnRichTextEditorInit event
		$onRichTextEditorInit = $modx->invokeEvent('OnRichTextEditorInit',array(
			'editor' => $rte,
			'elements' => $replace_richtexteditor,
		));
		if (is_array($onRichTextEditorInit)) {
			$onRichTextEditorInit = implode('',$onRichTextEditorInit);
			$modx->smarty->assign('onRichTextEditorInit',$onRichTextEditorInit);
        }
    }
}

$modx->smarty->assign('calpathhelp',str_replace('index.php','media/',$_SERVER['PHP_SELF']));

if (!isset($resource->content_type))
    $resource->set('content_type', 1);
$ar_cts= $modx->getCollection('modContentType', array ('binary' => 0));
$modx->smarty->assign('contentTypes',$ar_cts);


$modx->smarty->assign('resource',$resource);

$modx->smarty->display('resource/staticresource/create.tpl');

?>