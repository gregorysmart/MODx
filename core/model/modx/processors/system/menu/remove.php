<?php
/**
 * @package modx
 * @subpackage processors.system.menu
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('action','menu');

if (!$modx->hasPermission('menus')) $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_REQUEST['id'])) $modx->error->failure($modx->lexicon('menu_err_ns'));
$menu = $modx->getObject('modMenu',$_REQUEST['id']);
if ($menu == null) $modx->error->failure($modx->lexicon('menu_err_nf'));

if (!$menu->remove()) $modx->error->failure($modx->lexicon('menu_err_remove'));

/* log manager action */
$modx->logManagerAction('menu_delete','modMenu',$menu->get('id'));

$modx->error->success();