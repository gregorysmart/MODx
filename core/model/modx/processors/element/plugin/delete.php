<?php
/**
 * @package modx
 * @subpackage processors.element.plugin
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('plugin');

if (!$modx->hasPermission('delete_plugin')) $error->failure($modx->lexicon('permission_denied'));

// get plugin
$plugin = $modx->getObject('modPlugin', $_REQUEST['id']);
if ($plugin == null) $error->failure($modx->lexicon('plugin_err_not_found'));

// remove plugin
$plugin->remove();

// invoke OnPluginFormDelete event
$modx->invokeEvent('OnPluginFormDelete', array (
	'id' => $plugin->id
));

// log manager action
$modx->logManagerAction('plugin_delete','modPlugin',$plugin->id);

// empty cache
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

$error->success();