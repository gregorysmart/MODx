<?php
require_once (MODX_SETUP_PATH . 'includes/modinstall.class.php');

$installMode= $install->getInstallMode();
$this->parser->assign('installmode', $installMode);

$install->setConfig($installMode);
if ($installMode == 0) {
    $install->getAdminUser();
}
$this->parser->assign('config', $install->config);

$webUrl= substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], 'setup/'));

$this->parser->assign('context_web_path', MODX_INSTALL_PATH);
$this->parser->assign('context_web_url', $webUrl);
$this->parser->assign('context_connectors_path', MODX_INSTALL_PATH . 'connectors/');
$this->parser->assign('context_connectors_url', $webUrl . 'connectors/');
$this->parser->assign('context_mgr_path', MODX_INSTALL_PATH . 'manager/');
$this->parser->assign('context_mgr_url', $webUrl . 'manager/');

$navbar= '
<input type="button" value="'.$install->lexicon['next'].'" id="cmdnext" name="cmdnext" style="float:right;width:100px;" onclick="return doAction(\'contexts\');" />
<span style="float:right">&nbsp;</span>
<input type="button" value="'.$install->lexicon['back'].'" id="cmdback" name="cmdback" style="float:right;width:100px;" onclick="return goAction(\'database\');"/>
';
$this->parser->assign('navbar', $navbar);
$this->parser->display('contexts.tpl');
