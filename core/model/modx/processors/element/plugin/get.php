<?php
/**
 * @package modx
 * @subpackage processors.element.plugin
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('plugin');

// get plugin
$plugin = $modx->getObject('modPlugin', $_REQUEST['id']);
if ($plugin == null) $modx->error->failure($modx->lexicon('plugin_err_not_found'));

$modx->error->success('',$plugin);