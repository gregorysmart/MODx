<?php
/**
 * Runs a module
 * 
 * @package modx
 * @subpackage manager.element.module
 */

if (!$modx->hasPermission('exec_module')) $modx->error->failure($modx->lexicon('access_denied'));

$modx->loadProcessor('element/module/run.php');
exit();