<?php
/**
 * @package modx
 * @subpackage processors.element.plugin.event
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('plugin');

if (!isset($_POST['plugin']) || !isset($_POST['event'])) {
    $modx->error->failure($modx->lexicon('plugin_event_err_ns'));
}
$pe = $modx->getObject('modPluginEvent',array(
    'pluginid' => $_POST['plugin'],
    'evtid' => $_POST['event'],
));
if ($pe == null) $modx->error->failure($modx->lexicon('plugin_event_err_nf'));
if ($pe->remove() === false) {
    $modx->error->failure($modx->lexicon('plugin_event_err_remove'));
}

$modx->error->success();