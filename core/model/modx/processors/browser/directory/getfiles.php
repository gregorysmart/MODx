<?php
/**
 * @package modx
 * @subpackage processors.browser.directory
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('file');

$dir = !isset($_POST['dir']) || $_POST['dir'] == 'root' ? '' : $_POST['dir'];
$dir = trim($dir,'/');

$fullpath = $modx->config['base_path'].$modx->config['rb_base_dir'].'/'.$dir;
$odir = dir($fullpath);

$files = array();
while(false !== ($name = $odir->read())) {
	if('.' == $name || '..' == $name || '.svn' == $name) continue;

	$fullname = $fullpath.'/'.$name;
	if(!is_readable($fullname)) continue;

	if(!is_dir($fullname)) {
		$atmp = explode(".", $name);
		if (1 == sizeof($atmp)) { $fileExtension = ''; } else {
			$fileExtension = strtolower(array_pop($atmp));
		}
		$fileClass = $this->fileClass . $fileExtension;
		$size = @filesize($fullname);
		$url = $modx->config['base_url'].$dir.'/'.$name;
		$files[] = array(
			'name' => $name,
			'cls' => 'file',
			'url' => $url,
			'ext' => $fileExtension,
			'pathname' => $fullname,
			'lastmod' => filemtime($fullname),
			'disabled' => is_writable($fullname),
			'leaf' => true,
			'size' => $size,
            'menu' => array(
                array('text' => $modx->lexicon('file_remove'),'handler' => 'this.removeFile'),
            ),
		);
	}
}
$this->outputArray($files,$count);