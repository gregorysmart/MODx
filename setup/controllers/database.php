<?php
require_once (MODX_SETUP_PATH . 'includes/modinstall.class.php');

$installMode= $install->getInstallMode();
$this->parser->assign('installmode', $installMode);


$install->getConfig($installMode);
if ($installMode == 0) {
    $install->getAdminUser();
}
$this->parser->assign('config', $install->config);

$navbar= '
<input type="button" value="'.$install->lexicon['next'].'" id="cmdnext" name="cmdnext" style="float:right;width:100px;" onclick="return doAction(\'database\');" />
<span style="float:right">&nbsp;</span>
<input type="button" value="'.$install->lexicon['back'].'" id="cmdback" name="cmdback" style="float:right;width:100px;" onclick="return goAction(\'options\');"/>
';
$this->parser->assign('navbar', $navbar);
$this->parser->display('database.tpl');