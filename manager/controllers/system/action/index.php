<?php
/**
 * Loads action management
 *
 * @package modx
 * @subpackage manager.system.action
 */
if (!$modx->hasPermission('actions')) return $modx->error->failure($modx->lexicon('access_denied'));

return $modx->smarty->fetch('system/action/index.tpl');