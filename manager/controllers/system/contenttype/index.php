<?php
/**
 * Loads content type management
 *
 * @package modx
 * @subpackage manager.system.contenttype
 */
if (!$modx->hasPermission('content_types')) return $modx->error->failure($modx->lexicon('access_denied'));

return $modx->smarty->fetch('system/contenttype/index.tpl');