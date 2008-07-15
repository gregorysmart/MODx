<?php
/**
 * Duplicates a TV
 * 
 * @package modx
 * @subpackage manager.element.tv
 */
if(!$modx->hasPermission('edit_template')) $modx->error->failure($modx->lexicon('access_denied'));

$modx->loadProcessor('element/tv/duplicate.php');

// redirect
header('Location: index.php?a=element/tv/update.php&id='.$tv->id);
exit();