<?php
require_once dirname(dirname(__FILE__)).'/index.php';

$stay = isset($_SESSION['modx.stay']) ? $_SESSION['modx.stay'] : 'stay';
$modx->getVersionData();

$template_url = $modx->config['manager_url'].'templates/'.$modx->config['manager_theme'].'/';
$c = array(
    'stay' => $stay,
    'base_url' => $modx->config['base_url'],
    'connectors_url' => $modx->config['connectors_url'],
    'icons_url' => $template_url.'images/ext/modext/',
    'manager_url' => $modx->config['manager_url'],
    'template_url' => $template_url,
    'user' => $modx->user->id,
    'version' => $modx->version['full_version'], 
);

// if custom context, load into MODx.config
if (isset($_REQUEST['action']) && isset($_REQUEST['ctx'])
    && $_REQUEST['ctx'] != 'mgr' && $_REQUEST['ctx'] != ''
    && $_REQUEST['action'] != '' && isset($modx->actionMap[$_REQUEST['action']])) {
    
    $action = $modx->actionMap[$_REQUEST['action']];
    $c['context'] = $action['context'];
    $c['context_path'] = $action['context_path'];
    $c['context_url'] = $action['context_url'];
}

$actions = $modx->request->getAllActionIDs();

$c = array_merge($modx->config,$c);

$o = "Ext.namespace('MODx'); MODx.config = ";
$o .= $modx->toJSON($c);
$o .= '; MODx.action = ';
$o .= $modx->toJSON($actions);
$o .= ';';

echo $o;