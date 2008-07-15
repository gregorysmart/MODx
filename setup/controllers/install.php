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

$results= $install->execute($installMode);
$this->parser->assign('results', $results);

$failed= false;
foreach ($results as $item) {
    if ($item['class'] === 'failed') {
        $failed= true;
        break;
    }
}
$this->parser->assign('failed', $failed);
$this->parser->assign('itemClass', $failed ? 'error' : '');

$nextButton= $failed ? $install->lexicon['retry'] : $install->lexicon['continue'];
$nextAction= $failed ? 'return goAction(\'install\')' : 'return doAction(\'install\');';
$backButton= $failed ? '<input type="button" value="'.$install->lexicon['back'].'" id="cmdback" name="cmdback" style="float:right;width:100px;" onclick="return goAction(\'contexts\');"/>' : '';
$navbar= '
<input type="button" value="' . $nextButton . '" id="cmdnext" name="cmdnext" style="float:right;width:100px;" onclick="' . $nextAction . '" />
<span style="float:right">&nbsp;</span>
';
$this->parser->assign('navbar', $navbar);
$this->parser->display('install.tpl');