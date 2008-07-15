<?php
/**
 * Displays module run errors
 * 
 * @package modx
 * @subpackage manager.element.module
 */
$modx->smarty->assign('error',$error);
$modx->smarty->display('element/module/error.tpl');