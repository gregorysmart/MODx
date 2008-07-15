<?php
/**
 * @package modx
 * @subpackage processors.element.module.dependency
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('module');


if (!$modx->hasPermission('delete_module')) $error->failure($modx->lexicon('permission_denied'));
if (!isset($_POST['data'])) $error->failure($modx->lexicon('module_err_dep_delete'));

$_DATA = $modx->fromJSON($_POST['data']);

$dep = $modx->getObject('modModuleDepobj',array('id'=>$_DATA['id']));
if (!$dep->remove()) $error->failure($modx->lexicon('module_err_dep_delete'));

// log manager action
$modx->logManagerAction('module_depobj_delete','modModuleDepobj',$dep->id);

$error->success();