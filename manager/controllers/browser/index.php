<?php
/**
 * Loads the MODx.Browser page 
 * 
 * @package modx
 * @subpackage manager.browser
 */
require_once dirname(__FILE__).'/init.php';

// eventually replace this line with rte-specific handler injected by transport
$rtecallback = "
	var fileUrl = unescape(data.url);
	window.top.opener.SetUrl(fileUrl);
";

$modx->smarty->assign('rtecallback',$rtecallback);
$modx->smarty->display('browser/index.tpl');