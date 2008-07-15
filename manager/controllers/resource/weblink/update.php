<?php
// check permissions
if (!$modx->hasPermission('edit_document')) $error->failure($modx->lexicon('access_denied'));

$resource = $modx->getObject('modWebLink',$_REQUEST['id']);
if ($resource == NULL) $error->failure('Resource not found!');

// restore saved form
$formRestored = false;
if ($modx->request->hasFormValues()) {
    $modx->request->loadFormValues();
    $formRestored = true;
}

if (!$resource->checkPolicy('save')) {
	?><br /><br /><div class="sectionHeader"><?php echo $modx->lexicon('access_permissions'); ?></div><div class="sectionBody">
    <p><?php echo $modx->lexicon('access_permission_denied'); ?></p>
    <?php
	exit;
}


if (isset($_REQUEST['template'])) $resource->set('template',$_REQUEST['template']);


// invoke OnDocFormPrerender event
$onDocFormPrerender = $modx->invokeEvent('OnDocFormPrerender',array('id' => 0));
if (is_array($onDocFormPrerender))
    $onDocFormPrerender = implode('',$onDocFormPrerender);
$modx->smarty->assign('onDocFormPrerender',$onDocFormPrerender);

// PARENT DOCUMENT
if ($resource->parent == 0) {
	$parentname = $modx->config['site_name'];
} else {
	$parent = $modx->getObject('modResource',$resource->parent);
	if ($parent == NULL) {
		$e->setError(8);
		$e->dumpError();
	}
	$parentname = $parent->pagetitle;
}
// Not used in template
$modx->smarty->assign('parentname',$parentname);

// KEYWORDS AND METATAGS
if($modx->hasPermission('edit_doc_metatags')) {

	// get list of site keywords - code by stevew! modified by Raymond
	$selected_keywords = array();
	$keywords_xref = $modx->getCollection('modResourceKeyword',array('content_id' => $resource->id));
	foreach ($keywords_xref as $kwx) {
		$kw = $modx->getObject('modKeyword',$kwx->keyword_id);
		if ($kw != NULL) $selected_keywords[] = $kw->id;
	}

	$c = $modx->newQuery('modKeyword');
	$c = $c->sortby('keyword','ASC');
	$keywords = $modx->getCollection('modKeyword',$c);
	foreach ($keywords as $k) {
		if (in_array($k->id,$selected_keywords))
			$k->set('selected',true);
	}
	$modx->smarty->assign('keywords',$keywords);



	// get list of site META tags
	$selected_metatags = array();
	$metatags_xref = $modx->getCollection('modResourceMetatag',array('content_id' => $resource->id));
	foreach ($metatags_xref as $mtx) {
		$mtx = $modx->getObject('modMetatag',$mtx->metatag_id);
		if ($mtx != NULL) $selected_metatags[] = $mtx->id;
	}
	$metatags = $modx->getCollection('modMetatag');
	foreach ($metatags as $m) {
		if (in_array($m->id,$selected_metatags))
			$m->set('selected',true);
	}
	$modx->smarty->assign('metatags',$metatags);

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
			IF(tvc.value != :blank,tvc.value,tv.default_text) AS value

		FROM '.$modx->getTableName('modTemplateVar').' AS tv

			INNER JOIN '.$modx->getTableName('modTemplateVarTemplate').' AS tvtpl
			ON tvtpl.tmplvarid = tv.id
			AND tvtpl.templateid = :template

			LEFT JOIN '.$modx->getTableName('modTemplateVarResource').' AS tvc
			ON tvc.tmplvarid = tv.id
			AND tvc.contentid = :document_id

			LEFT JOIN '.$modx->getTableName('modTemplateVarResourceGroup').' AS tva
			ON tva.tmplvarid = tv.id

		WHERE
			tv.category = :category
		ORDER BY tvtpl.rank,tv.rank
	',array(
		':blank' => '',
		':document_id' => $resource->id,
		':template' => $resource->template,
		':category' => $category->id,
	));
	$tvs = $modx->getCollection('modTemplateVar',$c);

	if (count($tvs) > 0) {
		foreach ($tvs as $tv) {
			// go through and display all the document variables
			if ($tv->type == 'richtext' || $tv->type == 'htmlarea') { // htmlarea for backward compatibility
				if (is_array($replace_richtexteditor))
					$replace_richtexteditor = array_merge($replace_richtexteditor, array (
						'tv' . $tv->name
					));
				else
					$replace_richtexteditor = array (
						'tv' . $tv->name
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
} else {
    $dgds = $resource->getMany('modResourceGroupResource');
}
foreach ($dgds as $dgd)
    $groupsarray[$dgd->document_group] = $dgd->document_group;

// retain selected doc groups between post
if (isset($_POST['docgroups'])) {
    $groupsarray = array_merge($groupsarray, $_POST['docgroups']);
}

$c = $modx->newQuery('modResourceGroup');
$c = $c->sortby('name','ASC');
$docgroups = $modx->getCollection('modResourceGroup',$c);
foreach ($docgroups as $docgroup) {
    $checked = in_array($docgroup->id,$groupsarray);
	$docgroup->selected= $checked;
}

$modx->smarty->assign('docgroups',$docgroups);
$modx->smarty->assign('hasdocgroups',count($docgroups) > 0);


// invoke OnDocFormRender event
$onDocFormRender = $modx->invokeEvent('OnDocFormRender',array('id' => 0));
if (is_array($onDocFormRender))
    $onDocFormRender = implode('',$onDocFormRender);
$modx->smarty->assign('onDocFormRender',$onDocFormRender);

$modx->smarty->assign('resource',$resource);

$modx->smarty->display('resource/weblink/update.tpl');

?>