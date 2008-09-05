<?php
/**
 * Load update template page 
 * 
 * @package modx
 * @subpackage manager.element.template
 */
if(!$modx->hasPermission('edit_template')) $modx->error->failure($modx->lexicon('access_denied'));

// check to see the template editor isn't locked
if ($msg= $modx->checkForLocks($modx->getLoginUserID(),16,'template')) $modx->error->failure($msg);

// load template
$template = $modx->getObject('modTemplate',$_REQUEST['id']);
if ($template == null) $modx->error->failure($modx->lexicon('template_err_not_found'));
if ($template->locked && !$modx->hasPermission('edit_locked')) $error->failure($modx->lexicon('template_err_locked'));

$template->category = $template->getOne('modCategory');

// invoke OnTempFormPrerender event
$onTempFormPrerender = $modx->invokeEvent('OnTempFormPrerender',array('id' => $_REQUEST['id']));
if (is_array($onTempFormPrerender)) $onTempFormPrerender = implode('',$onTempFormPrerender);
$modx->smarty->assign('onTempFormPrerender',$onTempFormPrerender);

// invoke OnTempFormRender event
$onTempFormRender = $modx->invokeEvent('OnTempFormRender',array('id' => $_REQUEST['id']));
if (is_array($onTempFormRender)) $onTempFormRender = implode('',$onTempFormRender);
$modx->smarty->assign('onTempFormRender',$onTempFormRender);

// assign template to parser and display page
$modx->smarty->assign('template',$template);
$modx->smarty->display('element/template/update.tpl');