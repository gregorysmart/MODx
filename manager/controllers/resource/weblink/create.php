<?php
/**
 *
 */
if (!$modx->hasPermission('new_document')) return $modx->error->failure($modx->lexicon('access_denied'));

$resourceClass= isset ($_REQUEST['class_key']) ? $_REQUEST['class_key'] : 'modWebLink';
$resourceDir= strtolower(substr($resourceClass, 3));

$delegateView= dirname(__FILE__) . '/' . $resourceDir . '/' . basename(__FILE__);
if (file_exists($delegateView)) {
    $overridden= include_once ($delegateView);
    if ($overridden !== false) {
        return true;
    }
}

$resource = $modx->newObject($resourceClass);

/* handle template inheritance */
if (isset($_REQUEST['parent'])) {
    $parent = $modx->getObject('modResource',$_REQUEST['parent']);
    if ($parent != null) {
        $modx->smarty->assign('parent',$parent);
    }
}

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

$modx->smarty->assign('resource',$resource);

return $modx->smarty->fetch('resource/weblink/create.tpl');