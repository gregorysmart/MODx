<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage modx
 */

/**
 * Smarty {actionButtons} function plugin
 *
 * Type:     function<br>
 * Name:     actionButtons<br>
 * Purpose:  print out MODx action buttons
 * @author Shaun McCormick <splittingred at gmail dot com>
 * @param array parameters
 * @param Smarty
 * @return string|null
 */
function smarty_function_actionbuttons($params, &$smarty)
{
	global $_lang;
	if (!isset($params['data']) || !is_object($params['data'])) return 'Please specify the data field.';
	$ab = $params['data'];
	
	$ret = $ab->renderJS();	
	$ret .= $ab->renderButtons();
	if ($ab->show_stay) $ret .= $ab->renderStay();
	
    return $ret;
    
}

/* vim: set expandtab: */

?>