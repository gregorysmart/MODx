<?php
/**
 * @package modx
 * @subpackage processors.system.language
 */

require_once MODX_PROCESSORS_PATH.'index.php';

// get languages
$loc = $modx->config['core_path'] . 'lexicon/';
$dir = dir($loc);
$languages = array();
while ($file = $dir->read()) {
	if(is_dir($loc.$file) && !in_array($file,array('.','..','.svn','country'))) {
		$endpos = strpos($file,'.');
		$languagename = trim($file);
		$languages[] = array(
			'value' => $languagename,
			'text' => ucwords(str_replace('_',' ',$languagename)),
		);
	}
}
$dir->close();

$this->outputArray($languages,$count);