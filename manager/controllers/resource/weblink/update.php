<?php
// check permissions
if (!$modx->hasPermission('edit_document')) $modx->error->failure($modx->lexicon('access_denied'));

$resource = $modx->getObject('modWebLink',$_REQUEST['id']);
if ($resource == null) $error->failure('Resource not found!');

$resourceClass= isset ($_REQUEST['class_key']) ? $_REQUEST['class_key'] : $resource->get('class_key');
$resourceDir= strtolower(substr($resourceClass, 3));

$delegateView= dirname(__FILE__) . '/' . $resourceDir . '/' . basename(__FILE__);
if (file_exists($delegateView)) {
    $overridden= include_once ($delegateView);
    if ($overridden !== false) {
        return;
    }
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
	if ($parent == null) $error->failure($modx->lexicon('access_denied'));
    if (!$parent->checkPolicy('add_children')) $error->failure($modx->lexicon('access_denied'));
	$parentname = $parent->pagetitle;
}
$modx->smarty->assign('parentname',$parentname);

// KEYWORDS AND METATAGS
if($modx->hasPermission('edit_doc_metatags')) {

	// get list of site keywords
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

$groupsarray = array ();
// set permissions on the document based on the permissions of the parent document
if (!empty ($_REQUEST['parent'])) {
    $dgds = $modx->getCollection('modResourceGroupResource',array('document' => $_REQUEST['parent']));
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

$modx->smarty->assign('calpathhelp',str_replace('index.php','media/',$_SERVER['PHP_SELF']));

$modx->smarty->assign('resource',$resource);

$modx->smarty->display('resource/weblink/update.tpl');
