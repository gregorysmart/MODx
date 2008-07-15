<?php
/**
 * Empties the recycle bin
 * 
 * @package modx
 * @subpackage manager.resource
 */
if(!$modx->hasPermission('delete_document')) $modx->error->failure($modx->lexicon('access_denied'));

$modx->loadProcessor('resource/empty_recycle_bin.php');

// redirect
header('Location: index.php?a=welcome');
exit();