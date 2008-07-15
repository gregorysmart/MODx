<?php
/**
 * Duplicates a template
 * 
 * @package modx
 * @subpackage manager.element.template
 */
if(!$modx->hasPermission('new_template')) $modx->error->failure($modx->lexicon('access_denied'));

$modx->loadProcessor('element/template/duplicate.php');

// redirect
header('Location: index.php?a=element/template/update&id='.$template->id);
exit();