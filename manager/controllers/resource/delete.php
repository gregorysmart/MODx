<?php
/**
 * Loads the delete resource processor
 * 
 * @package modx
 * @subpackage manager.resource
 */

if(!$modx->hasPermission('delete_document')) $modx->error->failure($modx->lexicon('access_denied'));

$modx->loadProcessor('resource/delete.php');

// refresh
header('Location: index.php?r=1&a=7');
exit();