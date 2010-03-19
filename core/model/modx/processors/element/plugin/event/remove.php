<?php
/**
 * @package modx
 * @subpackage processors.element.plugin.event
 */
if (!$modx->hasPermission('delete_plugin')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('plugin');

/* get plugin event */
if (empty($scriptProperties['plugin']) || empty($scriptProperties['event'])) {
    return $modx->error->failure($modx->lexicon('plugin_event_err_ns'));
}
$pluginEvent = $modx->getObject('modPluginEvent',array(
    'pluginid' => $scriptProperties['plugin'],
    'evtid' => $scriptProperties['event'],
));
if (empty($pluginEvent)) return $modx->error->failure($modx->lexicon('plugin_event_err_nf'));

/* remove plugin event */
if ($pluginEvent->remove() === false) {
    return $modx->error->failure($modx->lexicon('plugin_event_err_remove'));
}

/* invoke system event */
$modx->invokeEvent('OnPluginEventRemove',array(
    'id' => $pluginEvent->get('id'),
    'pluginEvent' => &$pluginEvent,
));

return $modx->error->success('',$pluginEvent);