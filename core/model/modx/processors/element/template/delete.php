<?php
/**
 * @package modx
 * @subpackage processors.element.template
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('template','tv');

if (!$modx->hasPermission('delete_template')) $error->failure($modx->lexicon('permission_denied'));

// get template and related tables
$template = $modx->getObject('modTemplate',$_REQUEST['id']);
if ($template == null) $error->failure($modx->lexicon('template_err_not_found'));

// remove template var maps
$template->ttvs = $template->getMany('modTemplateVarTemplate');
foreach ($template->ttvs as $ttv)
	if (!$ttv->remove()) $error->failure($modx->lexicon('tvt_err_remove'));


// check to make sure it doesn't have any documents using it
$docs = $modx->getCollection('modResource',array(
	'deleted' => 0,
	'template' => $template->id,
));
if (count($docs) > 0) {
	$ds = '';
	foreach ($docs as $doc)
		$ds .= $doc->id.' - '.$doc->pagetitle." <br />\n";

	$error->failure($modx->lexicon('template_err_in_use').$ds);
}

// make sure isn't default template
if ($template->id == $default_template) {
	$error->failure($modx->lexicon('template_err_default_template'));
}

// invoke OnBeforeTempFormDelete event
$modx->invokeEvent('OnBeforeTempFormDelete',array('id' => $template->id));

// delete template
if (!$template->remove())
	$error->failure($modx->lexicon('template_err_delete'));

// invoke OnTempFormDelete event
$modx->invokeEvent('OnTempFormDelete',array('id' => $template->id));

// log manager action
$modx->logManagerAction('template_delete','modTemplate',$template->id);

// empty cache
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

$error->success();