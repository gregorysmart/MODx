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

/* get any module params for the snippet */
$c = new xPDOCriteria($modx,'
    SELECT
        sm.id,sm.name,sm.guid
    FROM '.$modx->getTableName('modModule').' AS sm
        INNER JOIN '.$modx->getTableName('modModuleDepobj').' AS smd
        ON smd.module = sm.id AND smd.type = 30

        INNER JOIN '.$modx->getTableName('modPlugin').' AS ss
        ON ss.id = smd.resource

    WHERE smd.resource = :resource AND sm.enable_sharedparams = 1
    ORDER BY sm.name
',array(
    ':resource' => $plugin->get('id'),
));
$params = $modx->getCollection('modModule',$c);
$modx->smarty->assign('params',$params);

/* load plugin into parser and display */
$modx->smarty->assign('plugin',$plugin);
$modx->smarty->display('element/plugin/update.tpl');
