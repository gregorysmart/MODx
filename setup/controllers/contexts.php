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

if ($installMode == 1) {
    $this->parser->assign('context_web_path', defined('MODX_BASE_PATH') ? MODX_BASE_PATH : MODX_INSTALL_PATH);
    $this->parser->assign('context_web_url', defined('MODX_BASE_URL') ? MODX_BASE_URL : $webUrl);
    $this->parser->assign('context_connectors_path', defined('MODX_CONNECTORS_PATH') ? MODX_CONNECTORS_PATH : MODX_INSTALL_PATH . 'connectors/');
    $this->parser->assign('context_connectors_url', defined('MODX_CONNECTORS_URL') ? MODX_CONNECTORS_URL : $webUrl . 'connectors/');
    $this->parser->assign('context_mgr_path', defined('MODX_MANAGER_PATH') ? MODX_MANAGER_PATH : MODX_INSTALL_PATH . 'manager/');
    $this->parser->assign('context_mgr_url', defined('MODX_MANAGER_URL') ? MODX_MANAGER_URL : $webUrl . 'manager/');
} else {
    $this->parser->assign('context_web_path', MODX_INSTALL_PATH);
    $this->parser->assign('context_web_url', $webUrl);
    $this->parser->assign('context_connectors_path', MODX_INSTALL_PATH . 'connectors/');
    $this->parser->assign('context_connectors_url', $webUrl . 'connectors/');
    $this->parser->assign('context_mgr_path', MODX_INSTALL_PATH . 'manager/');
    $this->parser->assign('context_mgr_url', $webUrl . 'manager/');
}

$navbar= '
<input type="button" value="'.$install->lexicon['next'].'" id="cmdnext" name="cmdnext" style="float:right;width:100px;" onclick="return doAction(\'contexts\');" />
<span style="float:right">&nbsp;</span>
<input type="button" value="'.$install->lexicon['back'].'" id="cmdback" name="cmdback" style="float:right;width:100px;" onclick="return goAction(\'database\');"/>
';
$this->parser->assign('navbar', $navbar);
$this->parser->display('contexts.tpl');
