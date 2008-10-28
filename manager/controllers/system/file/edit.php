<?php
/**
 * Loads the edit file page 
 * 
 * @package modx
 * @subpackage manager.system.file
 */
if (!$modx->hasPermission('file_manager')) return $modx->error->failure($modx->lexicon('access_denied'));
$modx->smarty->assign('file',$_GET['file']);
$modx->smarty->display('system/file/edit.tpl');