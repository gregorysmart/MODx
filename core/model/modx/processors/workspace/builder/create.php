<?php
/**
 * @package modx
 * @subpackage processors.workspace.builder
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('workspace','package_builder');

if (!$modx->hasPermission('package_builder')) $modx->error->failure($modx->lexicon('permission_denied'));

if (!isset($_POST['name']) || $_POST['name'] == '') {
    $modx->error->failure('Please specify a package name.');
}

/*
$modx->getService('registry','registry.modRegistry');
$modx->registry->addRegister('pb','registry.modFileRegister');
$modx->registry->pb->connect();

$modx->registry->pb->subscribe($_POST['name']);
*/

if (!isset($_SESSION['modx.pb'])) $_SESSION['modx.pb'] = array();

$_SESSION['modx.pb']['name'] = strtolower($_POST['name']);
$_SESSION['modx.pb']['version'] = strtolower($_POST['version']);
$_SESSION['modx.pb']['release'] = strtolower($_POST['release']);
$_SESSION['modx.pb']['namespace'] = $_POST['namespace'];
$_SESSION['modx.pb']['vehicles'] = array();

$modx->error->success();