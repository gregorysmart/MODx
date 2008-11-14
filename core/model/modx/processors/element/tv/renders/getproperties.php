<?php
/**
 * Grabs a list of render properties for a TV render
 *
 * @param string $ctx (optional) The context by which to grab renders from.
 * Defaults to mgr.
 * @param string $type (optional) The type of render to grab properties for.
 * Defaults to default.
 * @param integer $tv (optional) The TV to prefill property values from.
 *
 * @package modx
 * @subpackage processors.element.tv.renders
 */
$modx->lexicon->load('tv_widget');

if (!isset($_REQUEST['ctx'])) $_REQUEST['ctx'] = 'mgr';
if (!isset($_REQUEST['type'])) $_REQUEST['type'] = 'default';

if (!isset($modx->smarty)) {
    $modx->getService('smarty', 'smarty.modSmarty', '', array(
        'template_dir' => $modx->config['manager_path'] . 'templates/' . $modx->config['manager_theme'] . '/',
    ));
}

$settings = array();
if (isset($_REQUEST['tv']) && $_REQUEST['tv'] != '') {
	$tv = $modx->getObject('modTemplateVar',$_REQUEST['tv']);
    if ($tv != null) {
        $params = $tv->get('display_params');
        $ps = explode('&',$params);
        foreach ($ps as $p) {
        	$param = explode('=',$p);
            if ($p[0] != '') $settings[$param[0]] = $param[1];
        }
    }
}
$modx->smarty->assign('params',$settings);

$renderPath = dirname(__FILE__).'/'.$_REQUEST['ctx'].'/properties/';
$renderFile = $renderPath.$_REQUEST['type'].'.php';

if (file_exists($renderFile)) {
    $o = require_once $renderFile;
} else {
	return $modx->error->failure($modx->lexicon('error'));
}

echo $o;
die();