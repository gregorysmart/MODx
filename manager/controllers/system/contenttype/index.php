<?php
/**
 * Loads content type management
 * 
 * @package modx
 * @subpackage manager.system.contenttype
 */
if (!$modx->hasPermission('content_types')) return $modx->error->failure($modx->lexicon('access_denied'));

$modx->smarty->display('system/contenttype/index.tpl');