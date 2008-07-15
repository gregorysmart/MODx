<?php
/**
 * Loads the module list page
 * 
 * @package modx
 * @subpackage manager.element.module
 */

if( !( $modx->hasPermission('new_module')
	|| $modx->hasPermission('edit_module')
	|| $modx->hasPermission('exec_module'))) $modx->error->failure($modx->lexicon('access_denied'));

$modx->smarty->display('element/module/list.tpl');