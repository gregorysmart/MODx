<?php
/**
 * Loads the create module page
 *
 * @package modx
 * @subpackage manager.element.module
 */
if (!$modx->hasPermission('new_module')) $modx->error->failure($modx->lexicon('access_denied'));

if (isset($_REQUEST['category'])) {
	$category = $modx->getObject('modCategory',$_REQUEST['category']);
	if ($category != null) $modx->smarty->assign('category',$category);
}

/* create globally unique identifiers (guid) */
function createGUID() {
    srand((double)microtime()*1000000);
    $r = rand() ;
    $u = uniqid(getmypid() . $r . (double)microtime()*1000000,1);
    $m = md5 ($u);
    return $m;
}

$_SESSION['itemname'] = 'New Module';
$wrap = 1;

/* invoke OnModFormPrerender event */
$onModFormPrerender = $modx->invokeEvent('OnModFormPrerender',array('id' => 0));
if(is_array($onModFormPrerender)) $onModFormPrerender = implode('',$onModFormPrerender);
$modx->smarty->assign('onModFormPrerender',$onModFormPrerender);

$guid = createGUID();
$modx->smarty->assign('guid',$guid);

$usergroups = $modx->getCollection('modUserGroup');
$modx->smarty->assign('usergroups',$usergroups);

/* invoke OnModFormRender event */
$onModFormRender = $modx->invokeEvent('OnModFormRender',array('id' => 0));
if (is_array($onModFormRender)) $onModFormRender = implode('',$onModFormRender);
$modx->smarty->assign('onModFormRender',$onModFormRender);


$modx->smarty->display('element/module/create.tpl');