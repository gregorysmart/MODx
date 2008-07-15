<?php
/**
 * Loads message management 
 * 
 * @package modx
 * @subpackage manager.security.message
 */
if (!$modx->hasPermission('messages')) $modx->error->failure($modx->lexicon('access_denied'));

$modx->smarty->display('security/message/list.tpl');