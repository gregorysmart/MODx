<?php
/**
 * @package modx
 * @subpackage processors.element.template.tv
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('tv');

$_DATA = $modx->fromJSON($_POST['data']);
if ($_DATA['priority'] == '') $_DATA['priority'] = 0;

$pe = $modx->getObject('modPluginEvent',array(
    'pluginid' => $_DATA['plugin'],
    'evtid' => $_DATA['id'],
));

if ($_DATA['enabled']) {
    // enabling system event or editing priority
    if ($pe == null) {
        $pe = $modx->newObject('modPluginEvent');
    }
    $pe->set('pluginid',$_DATA['plugin']);
    $pe->set('evtid',$_DATA['id']);
    $pe->set('priority',$_DATA['priority']);
    
    if (!$pe->save()) $modx->error->failure($modx->lexicon('plugin_event_err_save'));
} else {
    // removing access    
    if ($pe == null) $modx->error->failure($modx->lexicon('plugin_event_err_nf'));

    if (!$pe->remove()) $modx->error->failure($modx->lexicon('plugin_event_err_remove'));
}

$modx->error->success();