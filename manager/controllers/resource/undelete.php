<?php
/**
 * Undeletes a resource 
 * 
 * @package modx
 * @subpackage manager.resource
 */

if(!$modx->hasPermission('delete_document')) $modx->error->failure($modx->lexicon('access_denied'));

$modx->loadProcessor('resource/undelete.php');

// redirect
header('Location: index.php?a=resource/update&id='.$document->id);
exit();