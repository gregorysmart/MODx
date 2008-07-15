<?php
require_once (MODX_SETUP_PATH . 'includes/modinstall.class.php');

$installMode= $install->getInstallMode();
$this->parser->assign('installmode', $installMode);

$install->setConfig($installMode);
if ($installMode == 0) {
    $install->getAdminUser();
}
$install->getContextPaths();
$this->parser->assign('config', $install->config);

$results= $install->test($installMode);
$this->parser->assign('test', $results);

$failed= false;
foreach ($results as $item) {
    if ($item['class'] === 'testFailed') {
        $failed= true;
        break;
    }
}
$this->parser->assign('failed', $failed);
$this->parser->assign('testClass', $failed ? 'error' : 'success');

$nextButton= $failed ? $install->lexicon['retry'] : $install->lexicon['install'];
$nextAction= $failed ? 'return goAction(\'summary\')' : 'return goAction(\'install\');';

$navbar= '
<input type="button" value="' . $nextButton . '" id="cmdnext" name="cmdnext" style="float:right;width:100px;" onclick="' . $nextAction . '" />
<span style="float:right">&nbsp;</span>
<input type="button" value="'.$install->lexicon['back'].'" id="cmdback" name="cmdback" style="float:right;width:100px;" onclick="return goAction(\'contexts\');"/>
';
$this->parser->assign('navbar', $navbar);
$this->parser->display('summary.tpl');
