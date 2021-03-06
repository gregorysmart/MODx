<?php
/**
 * Outputs the $modx->config to JSON
 *
 * @param string $action If set with context, will output the context info for a
 * custom context by the action
 * @param string $context If set with action, will output the context info for a
 * custom context by its action
 *
 * @package modx
 * @subpackage processors.system
 */
$modx->getVersionData();

if (!$modx->user->isAuthenticated('mgr')) { return ''; }
$template_url = $modx->getOption('manager_url').'templates/'.$modx->getOption('manager_theme').'/';
$c = array(
    'base_url' => $modx->getOption('base_url'),
    'connectors_url' => $modx->getOption('connectors_url'),
    'icons_url' => $template_url.'images/ext/modext/',
    'manager_url' => $modx->getOption('manager_url'),
    'template_url' => $template_url,
    'http_host' => MODX_HTTP_HOST,
    'site_url' => MODX_SITE_URL,
    'http_host_remote' => MODX_URL_SCHEME.$_SERVER['HTTP_HOST'],
    'user' => $modx->user->get('id'),
    'version' => $modx->version['full_version'],
);

/* if custom context, load into MODx.config */
if (isset($scriptProperties['action']) && $scriptProperties['action'] != '' && isset($modx->actionMap[$scriptProperties['action']])) {

    $action = $modx->actionMap[$scriptProperties['action']];
    $c['namespace'] = $action['namespace'];
    $c['namespace_path'] = $action['namespace_path'];
    $c['help_url'] = $action['help_url'];
}

$actions = $modx->request->getAllActionIDs();

$c = array_merge($modx->config,$c);

unset($c['password']);
unset($c['username']);

$o = "Ext.namespace('MODx'); MODx.config = ";
$o .= $modx->toJSON($c);
$o .= '; MODx.action = ';
$o .= $modx->toJSON($actions);
$o .= '; MODx.perm = {};';
if ($modx->user) { $o .= 'MODx.user = {id:"'.$modx->user->get('id').'",username:"'.$modx->user->get('username').'"}'; }

header('Content-Type: application/x-javascript');
echo $o;
die();