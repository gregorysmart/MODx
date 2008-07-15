<?php
/**
 * @package modx
 * @subpackage processors.workspace.builder.vehicle
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('workspace','package_builder');

array_splice($_SESSION['modx.pb']['vehicles'],$_POST['index'],1);

$modx->error->success();