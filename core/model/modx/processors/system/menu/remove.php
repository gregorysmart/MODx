<?php
/**
 * @package modx
 * @subpackage processors.system.menu
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('action','menu');

if (!isset($_REQUEST['id'])) $error->failure($modx->lexicon('menu_err_ns'));
$menu = $modx->getObject('modMenu',$_REQUEST['id']);
if ($menu == null) $error->failure($modx->lexicon('menu_err_nf'));

if (!$menu->remove()) $error->failure($modx->lexicon('menu_err_remove'));

// log manager action
$modx->logManagerAction('menu_delete','modMenu',$menu->id);

$error->success();