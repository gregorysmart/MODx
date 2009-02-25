<?php
/**
 * Load update plugin page
 *
 * @package modx
 * @subpackage manager.element.plugin
 */
if (!$modx->hasPermission('edit_plugin')) return $modx->error->failure($modx->lexicon('access_denied'));

/* load plugin */
$plugin = $modx->getObject('modPlugin',$_REQUEST['id']);
if ($plugin == null) return $modx->error->failure($modx->lexicon('plugin_not_found'));
$plugin->category = $plugin->getOne('modCategory');

/* invoke OnPluginFormPrerender event */
$onPluginFormPrerender = $modx->invokeEvent('OnPluginFormPrerender',array('id' => $_REQUEST['id']));
if (is_array($onPluginFormPrerender)) $onPluginFormPrerender = implode('',$onPluginFormPrerender);
$modx->smarty->assign('onPluginFormPrerender',$onPluginFormPrerender);

/* invoke OnPluginFormRender event */
$onPluginFormRender = $modx->invokeEvent('OnPluginFormRender',array('id' => $_REQUEST['id']));
if (is_array($onPluginFormRender)) $onPluginFormRender = implode('',$onPluginFormRender);
$modx->smarty->assign('onPluginFormRender',$onPluginFormRender);

/* check unlock default element properties permission */
$modx->smarty->assign('unlock_element_properties',$modx->hasPermission('unlock_element_properties') ? 1 : 0);

/* load plugin into parser and display */
$modx->smarty->assign('plugin',$plugin);
return $modx->smarty->fetch('element/plugin/update.tpl');
