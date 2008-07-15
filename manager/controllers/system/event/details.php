<?php

if(!$modx->hasPermission('view_eventlog')) $error->failure($modx->lexicon('access_denied'));

$event = $modx->getObject('modEventLog',$_REQUEST['id']);
if ($event == NULL) $e->failure('Event not found!');

$event->user = $modx->getObject('modUser', $event->user);

$modx->smarty->assign('event',$event);
$modx->smarty->display('system/event/details.tpl');
?>