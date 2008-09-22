<?php
/**
 * Publishes a resource
 * 
 * @package modx
 * @subpackage manager.resource
 */
if(!$modx->hasPermission(array('save_document' => true,'publish_document' => true))) $modx->error->failure($modx->lexicon('access_denied'));


$modx->loadProcessor('resource/publish.php');

header('Location: index.php?a=resource/update&id='.$document->id);
exit();