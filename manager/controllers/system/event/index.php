<?php
/**
 * Loads the system events page 
 * 
 * @package modx
 * @subpackage manager.system.event
 */
if(!$modx->hasPermission('view_eventlog')) return $modx->error->failure($modx->lexicon('access_denied'));

$modx->smarty->display('system/event/list.tpl');