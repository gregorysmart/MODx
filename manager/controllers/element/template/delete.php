<?php
/**
 * Deletes a template 
 * 
 * @package modx
 * @subpackage manager.element.template
 */
if(!$modx->hasPermission('delete_template')) $modx->error->failure($modx->lexicon('access_denied'));	

$modx->loadProcessor('element/template/delete.php');

// redirect
header('Location: index.php?a=welcome');
exit();