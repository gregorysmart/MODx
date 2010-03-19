<?php
/**
 * Gets a list of system events
 *
 * @package modx
 * @subpackage processors.element.plugin.event
 */
if (!$modx->hasPermission('view_plugin')) return $modx->error->failure($modx->lexicon('permission_denied'));
$modx->lexicon->load('plugin','system_events');

/* setup default properties */
$isLimit = empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,10);
$sort = $modx->getOption('sort',$scriptProperties,'name');
$dir = $modx->getOption('dir',$scriptProperties,'ASC');
$name = $modx->getOption('name',$scriptProperties,false);
$event = $modx->getOption('event',$scriptProperties,false);

$c = $modx->newQuery('modPlugin');
if (!empty($name)) {
     $c->where(array('name:LIKE' => '%'.$name.'%'));
}
if (!empty($event)) {
    $c->innerJoin('modPluginEvent','modPluginEvent','
        `modPluginEvent`.`pluginid` = `modPlugin`.`id`
    AND `modPluginEvent`.`evtid` = '.$event.'
    ');
    $c->select('
        `modPlugin`.*,
        IF(ISNULL(`modPluginEvent`.`pluginid`),0,1) AS `enabled`,
        `modPluginEvent`.`priority` AS `priority`,
        `modPluginEvent`.`propertyset` AS `propertyset`
    ');
}
$count = $modx->getCount('modPlugin',$c);

$c->sortby($sort,$dir);
if ($isLimit) $c->limit($limit,$start);
$plugins = $modx->getCollection('modPlugin',$c);

$list = array();
foreach ($plugins as $plugin) {
    $pluginArray = $plugin->toArray();

    $list[] = array(
        $pluginArray['id'],
        $pluginArray['name'],
        $pluginArray['priorityy'],
        $pluginArray['propertyset'],
        array(),
    );
}
return $modx->error->success('',$list);