<?php
/**
 * Loads the update module
 * 
 * @package modx
 * @subpackage manager
 */

if (!$modx->hasPermission('edit_module')) $modx->error->failure($modx->lexicon('access_denied'));

// create globally unique identifiers (guid)
function createGUID() {
    srand((double)microtime()*1000000);
    $r = rand() ;
    $u = uniqid(getmypid() . $r . (double)microtime()*1000000,1);
    $m = md5 ($u);
    return $m;
}


// check to see the editor isn't locked
if ($msg= $modx->checkForLocks($modx->getLoginUserID(),108,'module')) $modx->error->failure($msg);

$module = $modx->getObject('modModule',$_REQUEST['id']);
$wrap = $module->get('wrap');
if ($module == null)
	die('<p>No record found for id "'.$_REQUEST['id'].'".</p>');
$modx->smarty->assign('module',$module);

/* TODO: refactor locked principle to 097 standards
if ($module->locked == 1 && $_SESSION['mgrRole'] != 1) {
	$modx->error->failure($modx->lexicon('lock_module_msg'));
}
*/

// invoke OnModFormPrerender event
$onModFormPrerender = $modx->invokeEvent('OnModFormPrerender',array('id' => $_REQUEST['id']));
if(is_array($onModFormPrerender)) $onModFormPrerender = implode('',$onModFormPrerender);
$modx->smarty->assign('onModFormPrerender',$onModFormPrerender);

// load categories
$categories = $modx->getCollection('modCategory');
$modx->smarty->assign('categories',$categories);


// dependencies
$deps = $module->getDependencies();
$modx->smarty->assign('dependencies',$deps);

// fetch user access permissions for the module
$groupsarray = array();
$moduleugs = $modx->getCollection('modModuleUserGroup',array('module' => $module->id));

if ($moduleugs != NULL) {
    $moduleugs_count = count($moduleugs);
    foreach ($moduleugs as $ug) {
        $groupsarray[$i] = $ug->usergroup;
    }
}
$usergroups = $modx->getCollection('modUserGroup');
foreach ($usergroups as $ug) {
    if (in_array($ug->id,$groupsarray)) {
        $ug->set('checked',true);
        $modx->smarty->assign('notPublic',true);
    }
}
$modx->smarty->assign('usergroups',$usergroups);

// invoke OnModFormRender event
$onModFormRender = $modx->invokeEvent('OnModFormRender',array('id' => $module->id));
if (is_array($onModFormRender)) $onModFormRender = implode('',$onModFormRender);
$modx->smarty->assign('onModFormRender',$onModFormRender);

$modx->smarty->display('element/module/update.tpl');