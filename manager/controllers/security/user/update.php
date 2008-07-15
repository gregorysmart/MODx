<?php
/**
 * Loads update user page
 * 
 * @package modx
 * @subpackage manager.security.user
 */
if(!$modx->hasPermission('edit_user')) $modx->error->failure($modx->lexicon('access_denied'));

$user = $modx->getObject('modUser',$_REQUEST['id']);
if ($user == null) $modx->error->failure($modx->lexicon('user_err_nf'));

$user->profile = $user->getOne('modUserProfile',array('internalKey' => $user->id));
$user->getSettings();

// load Roles
$roles = $modx->getCollection('modUserRole');
$modx->smarty->assign('roles',$roles);

// invoke OnUserFormPrerender event
$onUserFormPrerender = $modx->invokeEvent('OnUserFormPrerender', array('id' => $_REQUEST['id']));
if (is_array($onUserFormPrerender))
	$onUserFormPrerender = implode('',$onUserFormPrerender);
$modx->smarty->assign('onUserFormPrerender',$onUserFormPrerender);

$blockedmode = ($user->profile->blocked || ($user->profile->blockeduntil > time() && $user->profile->blockeduntil != 0)|| ($user->profile->blockedafter < time() && $user->profile->blockedafter != 0) || $user->profile->failedlogins > 3) ? 1: 0;
$modx->smarty->assign('blockedmode',$blockedmode);


// include the country list language file
$_country_lang = array();
include_once $modx->config['core_path'].'lexicon/country/en.inc.php';
if ($modx->config['manager_language'] != 'en' && file_exists($modx->config['core_path'].'lexicon/country/'.$modx->config['manager_language'].'.inc.php')) {
    include_once $modx->config['core_path'].'lexicon/country/'.$modx->config['manager_language'].'.inc.php';
}
$modx->smarty->assign('_country_lang',$_country_lang);



// invoke onInterfaceSettingsRender event
$onInterfaceSettingsRender = $modx->invokeEvent('OnInterfaceSettingsRender', array('id' => $user->id));
if (is_array($onInterfaceSettingsRender))
	$onInterfaceSettingsRender = implode('', $onInterfaceSettingsRender);
$modx->smarty->assign('onInterfaceSettingsRender',$onInterfaceSettingsRender);


// load Access Permissions
$groupsarray = array();
$usergroups = $modx->getCollection('modUserGroup');
$ugus = $modx->getCollection('modUserGroupMember',array('member' => $user->id));

foreach ($ugus as $g) {
    $groupsarray[] = $g->user_group;
}

// retain selected doc groups between post
if (is_array($_POST['user_groups'])) {
    foreach ($_POST['user_groups'] as $n => $v)
        $groupsarray[] = $v;
}
$modx->smarty->assign('usergroups',$usergroups);
$modx->smarty->assign('groupsarray',$groupsarray);


$modx->smarty->assign('user',$user);
$modx->smarty->display('security/user/update.tpl');