<?php
/**
 * @package modx
 * @subpackage processors.element.snippet
 */

require_once MODX_PROCESSORS_PATH.'index.php';
$modx->lexicon->load('snippet');

if (!$modx->hasPermission('delete_snippet')) $modx->error->failure($modx->lexicon('permission_denied'));

// get snippet
$snippet = $modx->getObject('modSnippet',$_REQUEST['id']);
if ($snippet == null) $modx->error->failure($modx->lexicon('snippet_err_not_found'));

$modx->error->success('',$snippet);