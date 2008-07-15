<?php
/**
 * Loads the duplicate resource processor
 * 
 * @package modx
 * @subpackage manager.resource
 */

if(!$modx->hasPermission('new_document')) $error->failure($modx->lexicon('access_denied'));

$modx->loadProcessor('resource/duplicate.php');

// refresh
header('Location: index.php?a=resource/update&id='.$document->id);
exit();