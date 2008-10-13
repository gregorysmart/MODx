<?php
/**
 * @package modx
 * @subpackage processors.element.snippet
 */
require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('snippet');

if (!$modx->hasPermission('delete_snippet')) $modx->error->failure($modx->lexicon('permission_denied'));

/* get snippet */
$snippet = $modx->getObject('modSnippet',$_REQUEST['id']);
if ($snippet == null) $modx->error->failure($modx->lexicon('snippet_err_not_found'));

/* invoke OnBeforeSnipFormDelete event */
$modx->invokeEvent('OnBeforeSnipFormDelete',array('id' => $snippet->get('id')));

/* remove snippet */
if ($snippet->remove() == false) {
	$modx->error->failure($modx->lexicon('snippet_err_delete'));
}

/* invoke OnSnipFormDelete event */
$modx->invokeEvent('OnSnipFormDelete',array('id' => $snippet->get('id')));

/* log manager action */
$modx->logManagerAction('snippet_delete','modSnippet',$snippet->get('id'));

/* empty cache */
$cacheManager= $modx->getCacheManager();
$cacheManager->clearCache();

$modx->error->success();