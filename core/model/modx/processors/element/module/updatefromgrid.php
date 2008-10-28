<?php
/**
 * @package modx
 * @subpackage processors.element.module
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('module','category','user');

if (!$modx->hasPermission('save_module')) return $modx->error->failure($modx->lexicon('permission_denied'));

$_DATA = $modx->fromJSON($_POST['data']);

/* get module */
$module = $modx->getObject('modModule',$_DATA['id']);
if ($module == null) return $modx->error->failure($modx->lexicon('module_err_not_found'));

/* if locked, deny access */
if ($module->get('locked') && $modx->hasPermission('edit_locked') == false) {
    return $modx->error->failure($modx->lexicon('lock_module_msg'));
}

/* save module */
$module->set('description',$_DATA['description']);
$module->set('locked',$_DATA['locked']);
$module->set('disabled',$_DATA['disabled']);

if ($module->save() == false) {
    return $modx->error->failure($modx->lexicon('module_err_save'));
}

/* empty cache */
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

return $modx->error->success();