<?php
/**
 * Loads the manager logs page
 *
 * @package modx
 * @subpackage manager.system.logs
 */
if (!$modx->hasPermission('logs')) return $modx->error->failure($modx->lexicon('access_denied'));
return $modx->smarty->fetch('system/logs/index.tpl');