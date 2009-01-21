<?php
/**
 * Loads the system events page
 *
 * @package modx
 * @subpackage manager.system.event
 */
if(!$modx->hasPermission('view_eventlog')) return $modx->error->failure($modx->lexicon('access_denied'));

return $modx->smarty->fetch('system/event/list.tpl');