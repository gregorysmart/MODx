<?php
/**
 * Unpublishes a resource
 * 
 * @package modx
 * @subpackage manager.resource
 */
if(!$modx->hasPermission(array('save_document' => 1, 'publish_document' => 1))) $modx->error->failure($modx->lexicon('access_denied'));

$modx->loadProcessor('resource/unpublish.php');

header('Location: index.php?a=resource/update&id='.$document->id);
exit();