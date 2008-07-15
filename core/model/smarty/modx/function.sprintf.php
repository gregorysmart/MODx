<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage modx
 */

/**
 * Smarty {sprintf} function plugin
 *
 * Type:     function<br>
 * Name:     sprintf<br>
 * Purpose:  mimic sprintf for smarty templates
 * @author Shaun McCormick <splittingred at gmail dot com>
 * @param array parameters
 * @param Smarty
 * @return string|null
 */
function smarty_function_sprintf($params, &$smarty)
{
	global $_lang;
	if (!isset($params['string'])) return '';
	if (!isset($params['args'])) { 
		$params['args'] = array();	
	} elseif (!is_array($params['args'])) {
		$params['args'] = explode(',',$params['args']); 
	}
	
	$ret = call_user_func_array('sprintf',$params['args']);
		
    return $ret;
    
}

/* vim: set expandtab: */

?>