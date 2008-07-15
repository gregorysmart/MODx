<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage modx
 */

/**
 * Smarty {loadExpandables} function plugin
 *
 * Type:     function<br>
 * Name:     loadExpandables<br>
 * Purpose:  load Expandable javascript
 * @author Shaun McCormick <splittingred at gmail dot com>
 * @param array parameters
 * @param Smarty
 * @return string|null
 */
function smarty_function_loadExpandables($params, &$smarty)
{
	global $_lang;
	
	$ret = $smarty->fetch(MODX_SMARTY_TEMPLATES.'expandable/javascript.tpl');			
    return $ret;
    
}

/* vim: set expandtab: */

?>