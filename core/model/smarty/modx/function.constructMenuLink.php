<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage modx
 */

/**
 * Smarty {constructMenuLink} function plugin
 *
 * Type:     function<br>
 * Name:     constructMenuLink<br>
 * Purpose:  make menu link for left menu
 * @author Shaun McCormick <splittingred at gmail dot com>
 * @param array parameters
 * @param Smarty
 * @return string|null
 */
function smarty_function_constructMenuLink($params, &$smarty)
{
	$smarty->assign('p',$params);
	$ret = $smarty->fetch(MODX_SMARTY_TEMPLATES.'menulink.tpl');			
    return $ret;
}

/* vim: set expandtab: */

?>