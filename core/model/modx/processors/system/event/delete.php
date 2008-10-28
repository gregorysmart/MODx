<?php
/**
 * @package modx
 * @subpackage processors.system.event
 */
require_once MODX_PROCESSORS_PATH.'index.php';

if (!$modx->hasPermission('delete_eventlog')) return $modx->error->failure($modx->lexicon('permission_denied'));

$clearall = $_REQUEST['cls'] == 1 ? true : false;

if ($clearall) {
	$events = $modx->getCollection('modEventLog');
	foreach ($events as $event) {
        $event->remove();
    }
} else {
	$event = $modx->getObject('modEventLog', $_REQUEST['id']);
	$event->remove();
}

return $modx->error->success();