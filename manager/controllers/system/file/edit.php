<?php
/**
 * Loads the edit file page 
 * 
 * @package modx
 * @subpackage manager.system.file
 */
$modx->smarty->assign('file',$_GET['file']);
$modx->smarty->display('system/file/edit.tpl');