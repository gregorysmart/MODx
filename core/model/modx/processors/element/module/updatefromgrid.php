<?php
/**
 * @package modx
 * @subpackage processors.element.module
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('module','category','user');

if (!$modx->hasPermission('save_module')) $modx->error->failure($modx->lexicon('permission_denied'));

$_DATA = $modx->fromJSON($_POST['data']);

// get module
$module = $modx->getObject('modModule',$_DATA['id']);
if ($module == null) $modx->error->failure($modx->lexicon('module_err_not_found'));

$module->set('description',$_DATA['description']);
$module->set('locked',$_DATA['locked']);
$module->set('disabled',$_DATA['disabled']);

if (!$module->save()) $modx->error->failure($modx->lexicon('module_err_save'));

// empty cache
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

$modx->error->success();