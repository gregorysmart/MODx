<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage modx
 */
/**
 * Smarty {nicesize} modifier plugin
 *
 * Type:     modifier<br>
 * Name:     nicesize<br>
 * Date:     March 28th, 2007<br>
 * Purpose:  format nicely file sizes
 * @author   Shaun McCormick <splittingred at gmail dot com>
 * @version  1.0
 * @param string
 * @param string
 * @return string output of new size
 */
function smarty_modifier_nicesize($size)
{
	if (!isset($size) || !is_numeric($size) || $size == 0) return '0 B';
	$a = array('B','KB','MB','GB','TB','PB');
	$pos = 0;
	while ($size >= 1024) {
		   $size /= 1024;
		   $pos++;
	}
	return $size == 0
		? '-' 
		: round($size,2).' '.$a[$pos];
}
/* vim: set expandtab: */
?>
