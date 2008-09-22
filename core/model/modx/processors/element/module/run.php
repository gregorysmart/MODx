<?php
/**
 * @package modx
 * @subpackage processors.element.module
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('module');

function processError($error) {
	global $modx;
	include_once $modx->config['manager_path'].'controllers/header.php';
	include_once $modx->config['manager_path'].'controllers/element/module/error.php';
	include_once $modx->config['manager_path'].'controllers/footer.php';
	exit();
}

if (!$modx->hasPermission('exec_module')) processError($modx->lexicon('permission_denied'));
if (!isset($_REQUEST['id'])) processError($modx->lexicon('module_err_run'));

$module = $modx->getObject('modModule',$_REQUEST['id']);

if($module->disabled == 1) processError($modx->lexicon('module_disabled'));

if ($module->getMany('modModuleUserGroup')) {
	$memberships= $modx->user->getMany('modUserGroupMember');
	foreach($module->modModuleUserGroup as $usergroup) {
		foreach ($memberships as $membership) {
			if ($usergroup->usergroup == $membership->user_group) $has_access = true;
		}
	}
	if (!isset($has_access)) processError($modx->lexicon('permission_denied'));
}

// load module configuration
$parameter = array();
if (!empty($module->properties)) {
	$tmpParams = explode("&",$module->properties);
	for ($x=0; $x<count($tmpParams); $x++) {
		$pTmp = explode("=", $tmpParams[$x]);
		$pvTmp = explode(";", trim($pTmp[1]));
		if ($pvTmp[1]=='list' && $pvTmp[3]!="") $parameter[$pTmp[0]] = $pvTmp[3]; //list default
		else if ($pvTmp[1]!='list' && $pvTmp[2]!="") $parameter[$pTmp[0]] = $pvTmp[2];
	}
}

// store params inside event object
$modx->event->params = &$parameter;
if(is_array($parameter)) {
	extract($parameter, EXTR_SKIP);
}
ob_start();
	$mod = eval($module->modulecode);
	$msg = ob_get_contents();
ob_end_clean();
if ($php_errormsg) {
	// ignore php5 strict errors
	if(!strpos($php_errormsg,'Deprecated')) processError($modx->lexicon('module_err_run'));
}
unset($modx->event->params);
echo $mod.$msg;