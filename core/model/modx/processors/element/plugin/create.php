<?php
/**
 * Creates a plugin
 *
 * @param string $name The name of the plugin
 * @param string $plugincode The code of the plugin.
 * @param string $description (optional) A description of the plugin.
 * @param integer $category (optional) The category for the plugin. Defaults to
 * no category.
 * @param boolean $locked (optional) If true, can only be accessed by
 * administrators. Defaults to false.
 * @param boolean $disabled (optional) If true, the plugin does not execute.
 * @param json $events (optional) A json array of system events to associate
 * this plugin with.
 * @param json $propdata (optional) A json array of properties
 *
 * @package modx
 * @subpackage processors.element.plugin
 */
if (!$modx->hasPermission('new_plugin')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('plugin','category');

/* set default name */
if (empty($_POST['name'])) $_POST['name'] = $modx->lexicon('plugin_untitled');

/* check to see if name already exists */
$nameExists = $modx->getObject('modPlugin',array('name' => $_POST['name']));
if ($nameExists != null) $modx->error->addField('name',$modx->lexicon('plugin_err_exists_name'));

/* category */
if (!empty($_POST['category'])) {
    $category = $modx->getObject('modCategory',array('id' => $_POST['category']));
    if ($category == null) $modx->error->addField('category',$modx->lexicon('category_err_nf'));
}

if ($modx->error->hasError()) return $modx->error->failure();

$plugin = $modx->newObject('modPlugin');
$plugin->fromArray($_POST);
$plugin->set('locked',!empty($_POST['locked']));
$properties = null;
if (isset($_POST['propdata'])) {
    $properties = $_POST['propdata'];
    $properties = $modx->fromJSON($properties);
}
if (is_array($properties)) $plugin->setProperties($properties);

/* invoke OnBeforePluginFormSave event */
$modx->invokeEvent('OnBeforePluginFormSave',array(
    'mode' => 'new',
    'id' => 0,
    'plugin' => &$plugin,
));


if ($plugin->save() == false) {
    return $modx->error->failure($modx->lexicon('plugin_err_create'));
}

/* change system events */
if (isset($_POST['events'])) {
    $_EVENTS = $modx->fromJSON($_POST['events']);
    foreach ($_EVENTS as $id => $event) {
        if (!empty($event['enabled'])) {
            $pluginEvent = $modx->getObject('modPluginEvent',array(
                'pluginid' => $plugin->get('id'),
                'evtid' => $event['id'],
            ));
            if ($pluginEvent == null) {
                $pluginEvent = $modx->newObject('modPluginEvent');
            }
            $pluginEvent->set('pluginid',$plugin->get('id'));
            $pluginEvent->set('evtid',$event['id']);
            $pluginEvent->set('priority',$event['priority']);
            $pluginEvent->save();
        } else {
            $pluginEvent = $modx->getObject('modPluginEvent',array(
                'pluginid' => $plugin->get('id'),
                'evtid' => $event['id'],
            ));
            if ($pluginEvent == null) continue;
            $pluginEvent->remove();
        }
    }
}

/* invoke OnPluginFormSave event */
$modx->invokeEvent('OnPluginFormSave',array(
    'mode' => 'new',
    'id' => $plugin->get('id'),
    'plugin' => &$plugin,
));

/* log manager action */
$modx->logManagerAction('new_plugin','modPlugin',$plugin->get('id'));

/* empty cache */
if (!empty($_POST['clearCache'])) {
    $cacheManager= $modx->getCacheManager();
    $cacheManager->clearCache();
}
return $modx->error->success('',$plugin->get(array_diff(array_keys($plugin->_fields), array('plugincode'))));