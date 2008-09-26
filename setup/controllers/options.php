<?php
require_once (MODX_SETUP_PATH . 'includes/modinstall.class.php');

$this->parser->assign('installmode',$install->getInstallMode());

$files_exist= 0;
if (file_exists(MODX_INSTALL_PATH . 'manager/index.php') &&
	file_exists(MODX_INSTALL_PATH . 'index.php') &&
	file_exists(MODX_INSTALL_PATH . 'connectors/index.php')
) {
	$files_exist = !in_array(MODX_SETUP_KEY, array('@advanced', '@sdk')) ? 1 : 0;
}

$manifest= 0;
if (file_exists(MODX_CORE_PATH . 'packages/core/manifest.php')) {
    $manifest= 1;
}

$unpacked= 0;
if ($manifest && file_exists(MODX_CORE_PATH . 'packages/core/modWorkspace/')) {
    $unpacked= 1;
}

$safe_mode= @ ini_get('safe_mode');
$this->parser->assign('safe_mode', ($safe_mode ? 1 : 0));


$this->parser->assign('files_exist', $files_exist);
$this->parser->assign('manifest', $manifest);
$this->parser->assign('unpacked', $unpacked);

$navbar= '
<input type="button" value="'.$install->lexicon['next'].'" id="cmdnext" name="cmdnext" style="float:right;width:100px;" onclick="return doAction(\'options\');" />
<span style="float:right">&nbsp;</span>
<input type="button" value="'.$install->lexicon['back'].'" id="cmdback" name="cmdback" style="float:right;width:100px;" onclick="return goAction(\'welcome\');"/>
';
$this->parser->assign('navbar', $navbar);
$this->parser->display('options.tpl');