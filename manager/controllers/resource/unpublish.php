<?php
/**
 * Unpublishes a resource
 * 
 * @package modx
 * @subpackage manager.resource
 */
if(!$modx->hasPermission('save_document')||!$modx->hasPermission('publish_document')) $modx->error->failure($modx->lexicon('access_denied'));

$modx->loadProcessor('resource/unpublish.php');

header('Location: index.php?a=resource/update&id='.$document->id);
exit();